<?php
class MP_Swift_Connection_smtp_SMTPUTF8 extends MP_Swift_connection_
{
	public $Swift_Connection_type = 'SMTP';

	public static $DIR___ = __DIR__;

	function connect( $mail_id, $y )
	{
		$settings = get_option( MailPress::option_name_smtp );

		$conn = new Swift_SmtpTransportSMTPUTF8();

		$conn->setHost( $settings['server'] );
		$conn->setPort( $settings['port'] );

		if ( !empty( $settings['ssl'] ) )
			$conn->setEncryption( $settings['ssl'] );

		if ( empty( $settings['username'] ) && empty( $settings['password'] ) )
		{
			$y->log( "**** Empty user/password for SMTP connection ****" );
		}
		else
		{
			if ( !empty( $settings['username'] ) )
			{
				$conn->setUsername( $settings ['username'] );
				if ( !empty( $settings['password'] ) )
					$conn->setPassword( $settings ['password'] );
			}
		}

		// eventually popb4smtp ( other authentications are detected automatically )
		if ( isset( $settings['smtp-auth'] ) && ( !empty( $settings['smtp-auth'] ) ) )
		{
			switch ( $settings['smtp-auth'] )
			{
				case '@PopB4Smtp' :
					add_filter( 'MailPress_swift_registerPlugin', array( __CLASS__, 'registerPlugin' ), 8, 1 );
				break;
			}
		}

		return $conn;
	}

	public static function registerPlugin( $_this_swift )
	{
		$settings = get_option( MailPress::option_name_smtp );

		$_this_swift->registerPlugin( new Swift_Plugins_PopBeforeSmtpPlugin( $settings['pophost'], $settings['popport'] ) );

		return $_this_swift;
	}
}