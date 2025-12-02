<?php
class UserLogin
{
	public $logged_in;
	public $userdata;
	public $login_error;
	public $toast_message;

	public function check_userlogin()
	{
		if (isset($_SESSION['userdata']) && !empty($_SESSION['userdata']) && is_array($_SESSION['userdata']) && !isset($_POST['userdata'])) {
			$userdata = $_SESSION['userdata'];
			$userdata['post'] = false;
		}

		if (isset($_POST['userdata']) && !empty($_POST['userdata']) && is_array($_POST['userdata'])) {
			$userdata = $_POST['userdata'];
			$userdata['post'] = true;
		}

		if (!isset($userdata) || !is_array($userdata)) {
			$this->logout();

			return;
		}

		if ($userdata['post'] === true) {
			$post = true;
		} else {
			$post = false;
		}

		// Se for post, ou seja, o usuário está tentando fazer o login, chama a função de login
		if($post){
			if (!$this->login($userdata)) {
				$this->logout();
				return;
			}
		}
		unset($userdata['post']);

		// Se não for post, ou seja, o usuário já está logado, apenas continua o codigo sem fazer requisições ao banco de dados

		$this->logged_in = true;

		$this->userdata = $_SESSION['userdata'];

		if (isset($_SESSION['goto_url'])) {
			$goto_url = urldecode($_SESSION['goto_url']);

			unset($_SESSION['goto_url']);


			echo '<meta http-equiv="refresh" content="0; url=' . $goto_url . '">';
			echo '<script type="text/javascript">window.location.href = "' . $goto_url . '";</script>';
		}

		if (empty($userdata)) {
			$this->logged_in = false;
			$this->login_error = null;

			$this->logout();

			return;
		}
	}

	// Função para fazer o login
	protected function login($userdata)
	{
		// Extrair define as variáveis com base no array
		extract($userdata);


		// Tratamento de login para email
		if ($tipo == 'email') {
			if (!isset($email) || !isset($userdata['email'])) {
				$this->logged_in = false;
				$this->login_error = "Email não informado.";
				$this->toast_message = $this->Messages->error('Email não informado.');

				return false;
			}
			$query = $this->db->query('SELECT * FROM `vwLogin` WHERE email = ? LIMIT 1', array($email));
		} elseif ($tipo == 'cpf') {
			if (!isset($cpf) || !isset($userdata['cpf'])) {
				$this->logged_in = false;
				$this->login_error = "CPF não informado.";
				$this->toast_message = $this->Messages->error('CPF não informado.');

				return false;
			}

			$cpf = str_replace(array('.', '-'), '', $cpf);

			$query = $this->db->query('SELECT * FROM `vwLogin` WHERE cpf = ? LIMIT 1', array($cpf));
		} elseif ($tipo == 'usuario') {
			if (!isset($usuario) || !isset($userdata['usuario'])) {
				$this->logged_in = false;
				$this->login_error = "Usuário não informado.";
				$this->toast_message = $this->Messages->error('Usuário não informado.');

				return false;
			}

			$query = $this->db->query('SELECT * FROM `vwLogin` WHERE usuario = ? LIMIT 1', array($usuario));
		}

		if (!isset($senha) || !isset($userdata['senha'])) {
			$this->logged_in = false;
			$this->login_error = "Senha não informada.";
			$this->toast_message = $this->Messages->error('Senha não informada.');

			return false;
		}

		if (!$query) {
			$this->logged_in = false;
			$this->login_error = "Tivemos um problema inesperado. Tente novamente.";
			$this->toast_message = $this->Messages->error('Tivemos um problema inesperado. Tente novamente.');

			return false;
		}

		$fetch = $query->fetch(PDO::FETCH_ASSOC);

		if (!$fetch) {
			$this->logged_in  = false;
			$this->login_error = "Usuário não encontrado.";
			$this->toast_message = $this->Messages->error('Usuário não encontrado.');
			return false;
		}

		$user_id = (int) $fetch['id'];

		if (empty($user_id)) {
			$this->logged_in = false;
			$this->login_error = "Usuário não encontrado.";
			$this->toast_message = $this->Messages->error('Usuário não encontrado.');

			return false;
		}

		$user_status = $fetch['STATUS'];

		if ($user_status == 'F') {
			$this->logged_in = false;
			$this->login_error = "Usuário bloqueado.";
			$this->toast_message = $this->Messages->error('Usuário bloqueado.');

			return false;
		}

		if ($this->phpass->CheckPassword($senha, $fetch['senha'])) {
			if (session_id() != $fetch['sessionID'] && !$post) {
				$this->logged_in = false;
				$this->login_error = "Sessão inválida.";
				$this->toast_message = $this->Messages->error('Sessão inválida.');

				return false;
			}

			session_regenerate_id();
			$session_id = session_id();

			// Atribui os dados do usuário à variável de sessão
			$_SESSION['userdata'] = $fetch;

			$_SESSION['userdata']['senha'] = $senha;

			$_SESSION['userdata']['sessionID'] = $session_id;
			
			// Garante que os dados de permissão estão disponíveis
			$_SESSION['userdata']['idPermissao'] = isset($fetch['idPermissao']) ? $fetch['idPermissao'] : 3; // Default: usuário comum
			$_SESSION['userdata']['permissao'] = isset($fetch['permissao']) ? $fetch['permissao'] : 'USUARIO';
			$_SESSION['userdata']['idEvento'] = isset($fetch['idEvento']) ? $fetch['idEvento'] : null;

			$query = $this->db->query('UPDATE `tblUsuario` SET `sessionID` = ?, `token`= null WHERE `idPessoa` = ?',	array($session_id, $user_id));

			return true;
		} else {
			$this->logged_in = false;

			$this->login_error = "Senha inválida.";
			$this->toast_message = $this->Messages->error('Senha inválida.');

			return false;
		}
	}

	protected function logout($redirect = false)
	{
		$goto_url = $_SESSION['goto_url'];
		$_SESSION['userdata'] = array();

		unset($_SESSION['userdata']);

		// Limpa a sessão
		$_SESSION = array();

		session_regenerate_id();

		if ($goto_url) {
			$_SESSION['goto_url'] = $goto_url;
		}

		if ($redirect === true) {
			$this->goto_login();
		}
	}

	protected function goto_login()
	{
		if (defined('HOME_URI')) {
			$login_uri  = HOME_URI . '/login';

			$_SESSION['goto_url'] = urlencode($_SERVER['REQUEST_URI']);

			echo '<meta http-equiv="refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		}

		return;
	}

	final protected function goto_page($page_uri = null)
	{
		if (isset($_GET['url']) && !empty($_GET['url']) && !$page_uri) {
			$page_uri  = urldecode($_GET['url']);
		}

		if ($page_uri) {
			echo '<meta http-equiv="refresh" content="0; url=' . $page_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $page_uri . '";</script>';

			return;
		}
	}
}
