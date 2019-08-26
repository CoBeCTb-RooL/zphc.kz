<?php

/* newtopic_notify.txt */
class __TwigTemplate_0eb929edc19399753b4de06e7b51f305b55efc2970ac99250a887f2daeee5dfb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "Subject: Уведомление о новой теме — «";
        echo (isset($context["FORUM_NAME"]) ? $context["FORUM_NAME"] : null);
        echo "»

Здравствуйте, ";
        // line 3
        echo (isset($context["USERNAME"]) ? $context["USERNAME"] : null);
        echo "!

Вы получили это сообщение потому, что следите за форумом «";
        // line 5
        echo (isset($context["FORUM_NAME"]) ? $context["FORUM_NAME"] : null);
        echo "» на конференции «";
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "». В этом форуме с момента вашего последнего посещения появилась новая тема «";
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "»";
        if (((isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null) !== "")) {
            echo ", созданная пользователем ";
            echo (isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null);
        }
        echo ". Вы можете перейти по следующей ссылке, чтобы просмотреть её. Новые уведомления не будут приходить, пока вы не просмотрите форум.

";
        // line 7
        echo (isset($context["U_FORUM"]) ? $context["U_FORUM"] : null);
        echo "

Если вы больше не хотите следить за форумом, то либо щёлкните по находящейся в нём ссылке «Отписаться от форума», либо перейдите по следующей ссылке:

";
        // line 11
        echo (isset($context["U_STOP_WATCHING_FORUM"]) ? $context["U_STOP_WATCHING_FORUM"] : null);
        echo "

";
        // line 13
        echo (isset($context["EMAIL_SIG"]) ? $context["EMAIL_SIG"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "newtopic_notify.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 13,  51 => 11,  44 => 7,  30 => 5,  25 => 3,  19 => 1,);
    }
}
/* Subject: Уведомление о новой теме — «{FORUM_NAME}»*/
/* */
/* Здравствуйте, {USERNAME}!*/
/* */
/* Вы получили это сообщение потому, что следите за форумом «{FORUM_NAME}» на конференции «{SITENAME}». В этом форуме с момента вашего последнего посещения появилась новая тема «{TOPIC_TITLE}»<!-- IF AUTHOR_NAME !== '' -->, созданная пользователем {AUTHOR_NAME}<!-- ENDIF -->. Вы можете перейти по следующей ссылке, чтобы просмотреть её. Новые уведомления не будут приходить, пока вы не просмотрите форум.*/
/* */
/* {U_FORUM}*/
/* */
/* Если вы больше не хотите следить за форумом, то либо щёлкните по находящейся в нём ссылке «Отписаться от форума», либо перейдите по следующей ссылке:*/
/* */
/* {U_STOP_WATCHING_FORUM}*/
/* */
/* {EMAIL_SIG}*/
/* */
