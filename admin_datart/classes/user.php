<?php
/* ---------------------------
	DatArt
	Creation Date : 27/01/2017
	classes/user.php
	author : ApplePie Cie
---------------------------- */

class User{
	private $id;
	private $public_name;
	private $public_surname;
	private $function;
	private $login;
	private $email_adress;
	private $last_connection;
    private $status;
    
	function __construct($id = 0){
		if($id != 0){
			$res= requete_sql("SELECT id,public_name,public_surname,function,login,email_adress,last_connection,status FROM user WHERE id = '".$id."' ");
			$user = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $user['id'];	
			$this->public_name = $user['public_name'];	
			$this->public_surname = $user['public_surname'];	
			$this->function = $user['function'];	
			$this->login = $user['login'];	
			$this->email_adress = $user['email_adress'];	
			$this->last_connection = $user['last_connection'];	
			$this->status = $user['status'];	
		}
	}
	
	function getId(){
	    return $this->id;
	}
	
	function setPublicName($public_name){
		$this->public_name = $public_name;
		return TRUE;
	}

	function getPublicName(){
	    return $this->public_name;
	}
	
	function setPublicSurname($public_surname){
		$this->public_surname = $public_surname;
		return TRUE;
	}

	function getPublicSurname(){
	    return $this->public_surname;
	}
	
	function setFunction($function){
		$this->function = $function;
		return TRUE;
	}

	function getFunction(){
	    return $this->function;
	}
	
	function setLogin($login){
		$this->login = $login;
		return TRUE;
	}

	function getLogin(){
	    return $this->login;
	}
	
	function setEmailAdress($email_adress){
		$this->email_adress = $email_adress;
		return TRUE;
	}

	function getEmailAdress(){
	    return $this->email_adress;
	}

	function getLastConnection(){
	    return $this->last_connection;
	}
	
	function setStatus($status){
		$this->status = $status;
		return TRUE;
	}

	function getStatus(){
	    return $this->status;
	}


/*----------------------------------------------------------------
	Mise en place de la fonction de cryptage des mots de passe
	Pour l'utiliser $this->passwordCrypt()
----------------------------------------------------------------*/

	function passwordCrypt($password){

		/*----------------------------------
		Je commence par définir un "cost" optimal. Le cost est une suite d'itération qui permet d'augmenter la "solidité" du hash généré. Plus il est important, plus le hash est puissant (et donc difficile à décrypter). Mais la puissance demandée en calcul pour la machine augmente en conséquence.
		----------------------------------*/
		function optimalCost(){
			$timeTarget = 0.03;	// Durée de calcul de 30 millisecondes

			$cost = 8;	//Cost de base - Il est recommandé de partir sur une base entre 8 et 10.

			//Une forme de boucle que l'on a pas encore utilisée. A la différence d'un while classique la vérification de la condition s'effectue à la fin de l'exécution. Une itération est donc toujours effectuée au minimum. Ici la boucle prend en paramètre le temps d'execution définit par timeTarget. Tant que l'on est en dessous des 30 millisecondes, le cost augmente.
			do {
			    $cost++;
			    $start = microtime(TRUE);
			    password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
			    $end = microtime(TRUE);
			} while (($end - $start) < $timeTarget);

			return $cost;
		}

		$option = [
			'cost' => optimalCost(),
		];

		//Je rajoute un encodage en base64 sur le mot de passe hashé pour ajouter une "sécurité"
		$passwordCrypted = base64_encode(password_hash($password, PASSWORD_BCRYPT, $option));

		if ($passwordCrypted){
			return $passwordCrypted;
		}
		else{
			return FALSE;
		}

	}

/*----------------------------------------------------------------
	Reset Password
	Réinitialise le mot de passe utilisateur
	en le remplaçant par celui générique
----------------------------------------------------------------*/
	function resetPassword(){
		$resetPassword = requete_sql("UPDATE user SET password = '".addslashes($this->passwordCrypt(GENERIC_PASSWORD))."' WHERE id='".$this->id."' ");
		if ($resetPassword) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}


/*----------------------------------------------------------------
	Mise en place de la fonction de controle des mots de passe
	Pour l'utiliser User::passwordCheck()
----------------------------------------------------------------*/
	static function passwordCheck($password, $hashCrypt){
		if ( password_verify($password,base64_decode($hashCrypt)) ){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
/*----------------------------------------------------------------
	Connexion à l'application
	Récupère l'ID et le Mot de passe correspondant au login (du coup je me pose la question du risque des doublons de login... peut-être rajouter l'email en paramètre de vérification ?)
	Test la validité du mot de passe. Si password ok, mise à jours de la dernière connexion et retourne l'ID.
----------------------------------------------------------------*/
 	static function connect($login, $password){
 		$userConnect = requete_sql("SELECT id,password FROM user WHERE login='".$login."' ");
 		$userConnect = $userConnect->fetch(PDO::FETCH_ASSOC);
 		$passwordTest = User::passwordCheck($password, $userConnect['password']);
 		if ($passwordTest) {
 			$lastConnection = requete_sql("UPDATE user SET last_connection = now() WHERE id='".$userConnect['id']."' ");
 			return $userConnect['id'];
 		}
 		else{
 			return FALSE;
 		}
 	}
 	

/*----------------------------------------------------------------
	Deconnexion de l'application
----------------------------------------------------------------*/

/*----------------------------------------------------------------
	Fonction d'update de mot de passe depuis le compte utilisateur
----------------------------------------------------------------*/

	function updatePassword($oldPassword, $newPassword){
		$userPassword = requete_sql("SELECT password FROM user WHERE id='".$this->id."' ");
		$userPassword = $userPassword->fetch(PDO::FETCH_ASSOC);
		$passwordTest = User::passwordCheck($oldPassword, $userPassword['password']);
 		if ($passwordTest) {
 			$updatePassword = requete_sql("UPDATE user SET password = '".$this->passwordCrypt($newPassword)."' WHERE id='".$this->id."' ");
 			if ($updatePassword) {
 				return TRUE;
 			}
 			else{
 				return FALSE;
 			}
 		}
 		else{
 			return FALSE;
 		}
	}

/*----------------------------------------------------------------
	Synchronisation des données de l'utilisateur à la BD
	- Si c'est une création -> INSERT & création du mot de passe
		générique (crypté) défini dans le fichier config par
		la constante GENERIC_PASSWORD
	- Si c'est une modification ->UPDATE
	- L'update prend en compte la page active et fait la différence entre
	le compte utilisateur et la gestion de utilisateurs
----------------------------------------------------------------*/

	function synchroDb($oldPassword, $newPassword){
		if (empty($this->id)) {
			$create = requete_sql("INSERT INTO user VALUES(
				NULL,
				'".addslashes($this->public_name)."',
				'".addslashes($this->public_surname)."',
				'".addslashes($this->function)."',
				'".addslashes($this->login)."',
				'".addslashes($this->passwordCrypt(GENERIC_PASSWORD))."',
				'".addslashes($this->email_adress)."',
				NULL,
				'".addslashes($this->status)."'
				)");
			if ($create) {
				$this->id = $create;
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			if (strpos($_SERVER['PHP_SELF'],'admin_datart/users_management.php')) {
				$update = requete_sql("UPDATE user SET 
					public_name = '".addslashes($this->public_name)."',
					public_surname = '".addslashes($this->public_surname)."',
					function = '".addslashes($this->function)."',
					login = '".addslashes($this->login)."',
					email_adress = '".addslashes($this->email_adress)."',
					status = '".addslashes($this->status)."'
					WHERE id = '".$this->id."'
					");
				if ($update) {
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else{
				if(isset($newPassword) && isset($oldPassword)){
					$updatePassword = $this->updatePassword($oldPassword, $newPassword);
					if ($updatePassword) {
						$updateMail = requete_sql("UPDATE user SET email_adress = '".addslashes($this->email_adress)."' WHERE id = '".$this->id."' ");
						if ($updateMail) {
							return TRUE;
						}
						else{
							return FALSE;
						}
					}
					else{
						return FALSE;
					}
				}
				else{
					$updateMail = requete_sql("UPDATE user SET email_adress = '".addslashes($this->email_adress)."' WHERE id = '".$this->id."' ");
					if ($updateMail) {
						return TRUE;
					}
					else{
						return FALSE;
					}
				}
			}
		}
	}



/*----------------------------------------------------------------
	Suppresion du compte utilisateur ciblé après vérification
	du mot de passe.
----------------------------------------------------------------*/




/*----------------------------------------------------------------
	Formulaire de création ou édition d'un utilisateur
----------------------------------------------------------------*/

	function form($target, $action='', $description){
	?>
		<form action=<?= $target ?> method="POST" id="<?= $description; ?>-user-form" class="user-form">
			<h2><?= $action ?> un utilisateur :</h2>
			<div class="form-group">
				<label for="public_name">Prénom :</label>
				<input type="text" name="public_name" id="public_name" onblur="getNameForm()" value="<?= $this->public_name ?>" required <?= strpos($_SERVER['PHP_SELF'],'admin_datart/user_account.php')?'disabled':''; ?> />
			</div>
			<div class="form-group">
				<label for="public_surname">Nom :</label>
				<input type="text" name="public_surname" id="public_surname" onblur="getSurnameForm()" value="<?= $this->public_surname ?>" required <?= strpos($_SERVER['PHP_SELF'],'admin_datart/user_account.php')?'disabled':''; ?> />
			</div>
			<div class="form-group">
				<label for="function">Fonction :</label>
				<input type="text" name="function" id="function" value="<?= $this->function ?>" required <?= strpos($_SERVER['PHP_SELF'],'admin_datart/user_account.php')?'disabled':''; ?> />
			</div>
			<div class="form-group">
				<label for="login">Login :</label>
				<input type="text" name="login" id="login" value="<?= $this->login ?>" readonly />
			</div>
			<div class="form-group">
				<label for="email">Adresse e-mail :</label>
				<input type="mail" name="email" id="email" value="<?= $this->email_adress ?>" required/>
			</div>

			<?php
				if (strpos($_SERVER['PHP_SELF'],'admin_datart/users_management.php')) {
				?>
				<div class="form-group">
					<label for="status">Statut :</label>
					<select class="form-control" id="status" name="status">
					    <option value="0" <?= $this->status=='0'?'selected="selected"':''; ?> >Administrateur</option>
					    <option value="1" <?= $this->status=='1'?'selected="selected"':''; ?> >Utilisateur</option>
					</select>
				</div>
				<?php
				}
				else{
				?>
				<fieldset>
					<div class="form-group">
						<label for="old-password">Mot de passe actuel :</label>
						<input type="password" name="old-password" id="old-password" />
					</div>
					<div class="form-group">
						<label for="new-password">Nouveau mot de passe :</label>
						<input type="password" name="new-password" id="new-password" />
					</div>
				</fieldset>
			<?php
			}
			?>
			<input type="hidden" name="id" value="<?= $this->id; ?>">
			<input type="submit" value="<?= $action; ?>" />

		</form>
	<?php
	}


/*----------------------------------------------------------------
	Génération d'une liste de tout les utilisateurs
----------------------------------------------------------------*/
	static function listUsers(){
		$res = requete_sql("SELECT id FROM user WHERE id != '1' ORDER BY id ASC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $user) {
			$user = new User($user['id']);
			array_push($list, $user);
		}
		return $list;
	}


/*----------------------------------------------------------------
	Envoi l'objet User dans un array puis le converti en Json
----------------------------------------------------------------*/
	function toJson(){
		$userArray = array(
			"name" => $this->public_name,
			"surname" => $this->public_surname,
			"email" => $this->email_adress,
			"login" => $this->login 
		);
		return json_encode($userArray, JSON_PRETTY_PRINT);
	}
}