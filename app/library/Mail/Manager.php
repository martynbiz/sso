<?php
namespace App\Mail;

use Zend\Mail\Transport\TransportInterface;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Foil\Contracts\EngineInterface;
use Illuminate\View\FileViewFinder;
use App\Model\User;

/**
 * This is a mail manager for MWAuth, it just removes the need for mail code
 * stuffing up the controllers, and the repetitiveness of building a Message.
 */
class Manager
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var array
     */
    protected $options;

    /**
     * Pass in the transport object
     * @param Zend\Mail\Transport\TransportInterface $transport
     * @param Foil\EngineInterface $renderer
     * @param string $language
     * @param string|null $defaultLocale
     * @param Zend\I18n\Translator|null $translator
     */
    public function __construct(TransportInterface $transport, EngineInterface $renderer, $language, $defaultLocale=null, $translator=null, $options=array())
    {
        $this->locale = $language;
        $this->defaultLocale = $defaultLocale;
        $this->renderer = $renderer;
        $this->transport = $transport;
        $this->translator = $translator;
        $this->options = $options;
    }

    /**
     * Return the localized string
     *
     * Just a convenience function passing the key to the translator class.
     *
     * @param string $key
     * @return string
     */
    public function _($key) {
        if ($this->translator == null) return $key;
        return $this->translator->translate($key);
    }

    /**
     * Send a welcome email when users sign up
     */
    public function sendWelcomeEmail(User $user)
    {
        // create the message body from the templates and data
        $textTemplate = 'emails/welcome-%s-text';
        $htmlTemplate = 'emails/welcome-%s-html';
        $body = $this->createMessageBody($textTemplate, $htmlTemplate, array(
            'user' => $user,
        ));

        // create the message
        $message = new Message();

        $message->setBody($body);
        $message->setFrom('noreply@sso.vagrant', 'JapanTravel'); // JapanTravel <noreply@japantravel.com>
        $message->addTo($user->email, $user->name);
        $message->setSubject($this->_('subject_welcome'));

        $message->getHeaders()->get('content-type')->setType('multipart/alternative');
        $message->setEncoding("UTF-8");

        // send
        $this->send($message);
    }

    /**
     * Send a welcome email when users sign up
     */
    public function sendPasswordRecoveryToken(User $user, $emailRecoveryToken)
    {
        // create the message body from the templates and data
        $textTemplate = 'emails.resetpassword-%s-text';
        $htmlTemplate = 'emails.resetpassword-%s-html';
        $body = $this->createMessageBody($textTemplate, $htmlTemplate, array(
            'user' => $user,
            'token' => $emailRecoveryToken,
        ));

        // create the message
        $message = new Message();

        $message->setBody($body);
        $message->setFrom('support@japantravel.com', 'JapanTravel team');
        $message->addTo($user->email, $user->name);
        $message->setSubject($this->_('subject_password_recovery'));

        $message->getHeaders()->get('content-type')->setType('multipart/alternative');
        $message->setEncoding("UTF-8");

        // send
        $this->send($message);
    }

    /**
     * Will create a Zend\Mime\Message body for Message
     * @param string $textTemplate sprintf format string (e.g. )
     */
    protected function createMessageBody($textTemplateFormat, $htmlTemplateFormat, $data=array())
    {
        // we don't seem to have an exists function with this library, but it will
        // throw an error if the file doesn't exist. therefor, we will catch the
        // error and assume that we wanna use the default one

        try { // current language
            $textTemplate = sprintf($textTemplateFormat, $this->locale);
            $textContent = $this->renderer->render($textTemplate, $data);
        } catch (\InvalidArgumentException $e) { // fallback locale (e.g. "en")

            // if default is not set, throw the exception from the try block
            if (is_null(@$this->defaultLocale)) throw $e;

            // use default locale template. will throw exception if not found
            $textTemplate = sprintf($textTemplateFormat, $this->defaultLocale);
            $textContent = $this->renderer->render($textTemplate, $data);
        }

        $text = new MimePart($textContent);
        $text->type = "text/plain";

        try { // current language
            $htmlTemplate = sprintf($htmlTemplateFormat, $this->locale);
            $htmlContent = $this->renderer->render($htmlTemplate, $data);
        } catch (\InvalidArgumentException $e) { // fallback locale (e.g. "en")

            // if default is not set, throw the exception from the try block
            if (is_null(@$this->defaultLocale)) throw $e;

            // use default locale template. will throw exception if not found
            $htmlTemplate = sprintf($htmlTemplateFormat, $this->defaultLocale);
            $htmlContent = $this->renderer->render($htmlTemplate, $data);
        }

        $html = new MimePart($htmlContent);
        $html->type = "text/html";

        // build the body from text and html parts
        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        return $body;
    }

    /**
     * Generic send method to take care of building Message and sending
     * @param
     */
    public function send(Message $message)
    {
        $this->transport->send($message);
    }
}
