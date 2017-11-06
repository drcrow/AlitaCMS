<?php 
namespace App\Http\Controllers;
use App\Type as Type;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mailgun\Mailgun;

class EmailsController extends Controller {

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function viewEmail($lang, $email, $market){

		return view('emails/'.$lang.'/'.$email.'/index');

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function sendInvite(Request $request, $lang){


	    $user = $this->userInfo($request->user, true);

	    if(@$user->id != $request->user){//user not exist
	    	return $user;
	    }

	    //no me importa el parámetro, esto depende del pais
	    if($user->country == 'US'){
			$lang = 'en';
		}else{
			$lang = 'es';
		}

	    if($lang == 'en'){
	    	$subject = 'Mendoza has something to tell you';
		}else{
			$subject = 'Mendoza tiene algo que decirte';
		}

		$html = $this->processEmailView($user->country ,'invite');

		$this->superdupermegaarchiMailSender($request->email, $subject, $html);	

		app('App\Http\Controllers\CopaAPIController')->userShareProxy('email', $user->id, $lang);	

		return response()->json(array('status'=>'ok'));

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function sendRegister($market, $to){

		if($market == 'US'){
			$lang = 'en';
		}else{
			$lang = 'es';
		}

	    if($lang == 'en'){
	    	$subject = 'You\'re already participating for a trip to Mendoza. Start earning points!';
		}else{
			$subject = 'Ya estás registrado por un viaje a Mendoza. ¡Empieza a sumar!';
		}

		$html = $this->processEmailView($market, 'register');
		$this->superdupermegaarchiMailSender($to, $subject, $html);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function superdupermegaarchiMailSender($to, $subject, $html){
		# First, instantiate the SDK with your API credentials
		$mg = Mailgun::create('key-cd5ff1ffbd8a5d46f652e41736cb72d7');

		# Now, compose and send your message.
		# $mg->messages()->send($domain, $params);
		$mg->messages()->send('mg.copagrandslam.com', [
		  'from'    => 'no-reply@mg.copagrandslam.com',
		  'to'      => $to,
		  'subject' => $subject,
		  'html'    => $html
		]);

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function processEmailView($market, $email){

		if($market == 'US'){
			$lang = 'en';
		}else{
			$lang = 'es';
		}

		$view = view('emails/'.$lang.'/'.$email.'/index');
		$html = $view->render();
		$html = str_replace('images/', env('SITE_URL').'/IMG/emails/'.$lang.'/'.$email.'/images/', $html);


		//INVITE
		$links['invite']['CO']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_CO_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['CO']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_CO_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['CO']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_CO_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['CO']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_CO_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['CO']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_CO_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';

		$links['invite']['MX']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_MX_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['MX']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_MX_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['MX']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_MX_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['MX']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_MX_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['MX']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_MX_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';

		$links['invite']['PA']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_PA_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['PA']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_PA_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['PA']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_PA_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['PA']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_PA_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';
		$links['invite']['PA']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_PA_es_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_invitacion_01_spa';

		$links['invite']['US']['%copa%'] = 'https://www.copaair.com/en?d1=EM-PRO_MDCO_US_en_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_invitacion_01_eng';
		$links['invite']['US']['%cta%'] = 'http://promos.copaair.com/with-love-mendoza?d1=EM-PRO_MDCO_US_en_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_invitacion_01_eng';
		$links['invite']['US']['%terminos%'] = 'http://promos.copaair.com/with-love-mendoza/terms-and-conditions?d1=EM-PRO_MDCO_US_en_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_invitacion_01_eng';
		$links['invite']['US']['%politica%'] = 'https://www.copaair.com/en/web/privacy-policy?d1=EM-PRO_MDCO_US_en_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_invitacion_01_eng';
		$links['invite']['US']['%contactenos%'] = 'https://www.copaair.com/en/web/contact-us?d1=EM-PRO_MDCO_US_en_2017-EMMG-INV&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_invitacion_01_eng';

		//LAST REMINDER
		$links['last_reminder']['CO']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_CO_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['CO']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_CO_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['CO']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_CO_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['CO']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_CO_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['CO']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_CO_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';

		$links['last_reminder']['MX']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_MX_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['MX']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_MX_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['MX']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_MX_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['MX']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_MX_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['MX']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_MX_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';

		$links['last_reminder']['PA']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_PA_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['PA']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_PA_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['PA']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_PA_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['PA']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_PA_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';
		$links['last_reminder']['PA']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_PA_es_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_lastreminder_01_spa';

		$links['last_reminder']['US']['%copa%'] = 'https://www.copaair.com/en?d1=EM-PRO_MDCO_US_en_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_lastreminder_01_eng';
		$links['last_reminder']['US']['%cta%'] = 'http://promos.copaair.com/with-love-mendoza?d1=EM-PRO_MDCO_US_en_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_lastreminder_01_eng';
		$links['last_reminder']['US']['%terminos%'] = 'http://promos.copaair.com/with-love-mendoza/terms-and-conditions?d1=EM-PRO_MDCO_US_en_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_lastreminder_01_eng';
		$links['last_reminder']['US']['%politica%'] = 'https://www.copaair.com/en/web/privacy-policy?d1=EM-PRO_MDCO_US_en_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_lastreminder_01_eng';
		$links['last_reminder']['US']['%contactenos%'] = 'https://www.copaair.com/en/web/contact-us?d1=EM-PRO_MDCO_US_en_2017-EMMG-UREC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_lastreminder_01_eng';

		//REGISTER
		$links['register']['CO']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['CO']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['CO']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['CO']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['CO']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';

		$links['register']['MX']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['MX']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['MX']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['MX']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['MX']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';

		$links['register']['PA']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['PA']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['PA']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['PA']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';
		$links['register']['PA']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_registro_01_spa';

		$links['register']['US']['%copa%'] = 'https://www.copaair.com/en?d1=EM-PRO_MDCO_US_en_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_registro_01_eng';
		$links['register']['US']['%cta%'] = 'http://promos.copaair.com/with-love-mendoza?d1=EM-PRO_MDCO_US_en_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_registro_01_eng';
		$links['register']['US']['%terminos%'] = 'http://promos.copaair.com/with-love-mendoza/terms-and-conditions?d1=EM-PRO_MDCO_US_en_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_registro_01_eng';
		$links['register']['US']['%politica%'] = 'https://www.copaair.com/en/web/privacy-policy?d1=EM-PRO_MDCO_US_en_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_registro_01_eng';
		$links['register']['US']['%contactenos%'] = 'https://www.copaair.com/en/web/contact-us?d1=EM-PRO_MDCO_US_en_2017-EMMG-REG&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_registro_01_eng';

		//REMINDER
		$links['reminder']['CO']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['CO']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['CO']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['CO']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['CO']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_CO_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_CO_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';

		$links['reminder']['MX']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['MX']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['MX']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['MX']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['MX']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_MX_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_MX_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';

		$links['reminder']['PA']['%copa%'] = 'https://www.copaair.com/es?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['PA']['%cta%'] = 'http://promos.copaair.com/con-amor-mendoza/?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['PA']['%terminos%'] = 'http://promos.copaair.com/con-amor-mendoza/terminos-y-condiciones?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['PA']['%politica%'] = 'https://www.copaair.com/es/web/politica-privacidad?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';
		$links['reminder']['PA']['%contactenos%'] = 'https://www.copaair.com/es/web/contactenos?d1=EM-PRO_MDCO_PA_es_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_PA_es_2017&utm_content=cpa.1006_mendoza_reminder_01_spa';

		$links['reminder']['US']['%copa%'] = 'https://www.copaair.com/en?d1=EM-PRO_MDCO_US_en_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_reminder_01_eng';
		$links['reminder']['US']['%cta%'] = 'http://promos.copaair.com/with-love-mendoza?d1=EM-PRO_MDCO_US_en_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_reminder_01_eng';
		$links['reminder']['US']['%terminos%'] = 'http://promos.copaair.com/with-love-mendoza/terms-and-conditions?d1=EM-PRO_MDCO_US_en_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_reminder_01_eng';
		$links['reminder']['US']['%politica%'] = 'https://www.copaair.com/en/web/privacy-policy?d1=EM-PRO_MDCO_US_en_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_reminder_01_eng';
		$links['reminder']['US']['%contactenos%'] = 'https://www.copaair.com/en/web/contact-us?d1=EM-PRO_MDCO_US_en_2017-EMMG-REC&utm_source=mailgun&utm_medium=email&utm_campaign=PRO_MDCO_US_en_2017&utm_content=cpa.1006_mendoza_reminder_01_eng';




		//ADD URLS
		$html = str_replace('%copa%', $links[$email][$market]['%copa%'], $html);
		$html = str_replace('%cta%', $links[$email][$market]['%cta%'], $html);
		$html = str_replace('%terminos%', $links[$email][$market]['%terminos%'], $html);
		$html = str_replace('%politica%', $links[$email][$market]['%politica%'], $html);
		$html = str_replace('%contactenos%', $links[$email][$market]['%contactenos%'], $html);

		return $html;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//copiado de CopaAPIController porque no tengo tiempo de pensar como hacerlo mejor
	public function userInfo($id, $returnObject = false){
		$user = DB::table('users')
	        ->where('id', $id)
	        ->first();

	    if(@$user->id != $id || ($id+0) <= 0){
	    	return response()->json(array('status'=>'error', 'code'=>772, 'description'=>'user doesnt exists'));
	    }

	    $user->status = 'ok';
	    
	    if($returnObject){
	   		return $user;
	   	}else{
	   		return response()->json($user);
	   	}
	}
}//class
?>