<?php

/* topic_notify.txt */
class __TwigTemplate_c3041ccc155ce924a4d1219f32fce016afc726286aff0306f81b152b128603dc extends Twig_Template
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
        echo "Subject: Уведомление об ответе — «";
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "»

Здравствуйте, ";
        // line 3
        echo (isset($context["USERNAME"]) ? $context["USERNAME"] : null);
        echo "!

Вы получили это сообщение потому, что следите за темой «";
        // line 5
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "» на конференции «";
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "». В этой теме со времени вашего последнего посещения появилось новое сообщение";
        if (((isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null) !== "")) {
            echo ", оставленное пользователем ";
            echo (isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null);
        }
        echo ". Новые уведомления не будут приходить, пока вы не просмотрите тему.

Если вы хотите просмотреть самое новое сообщение с момента вашего последнего посещения, перейдите по следующей ссылке:
";
        // line 8
        echo (isset($context["U_NEWEST_POST"]) ? $context["U_NEWEST_POST"] : null);
        echo "

Если вы хотите просмотреть всю тему, перейдите по следующей ссылке:
";
        // line 11
        echo (isset($context["U_TOPIC"]) ? $context["U_TOPIC"] : null);
        echo "

Если вы хотите просмотреть форум, перейдите по следующей ссылке:
";
        // line 14
        echo (isset($context["U_FORUM"]) ? $context["U_FORUM"] : null);
        echo "

Если вы больше не хотите следить за темой, то либо щёлкните по находящейся в ней ссылке «Отписаться от темы», либо перейдите по следующей ссылке:
";
        // line 17
        echo (isset($context["U_STOP_WATCHING_TOPIC"]) ? $context["U_STOP_WATCHING_TOPIC"] : null);
        echo "

";
        // line 19
        echo (isset($context["EMAIL_SIG"]) ? $context["EMAIL_SIG"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "topic_notify.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 19,  61 => 17,  55 => 14,  49 => 11,  43 => 8,  30 => 5,  25 => 3,  19 => 1,);
    }
}
/* Subject: Уведомление об ответе — «{TOPIC_TITLE}»*/
/* */
/* Здравствуйте, {USERNAME}!*/
/* */
/* Вы получили это сообщение потому, что следите за темой «{TOPIC_TITLE}» на конференции «{SITENAME}». В этой теме со времени вашего последнего посещения появилось новое сообщение<!-- IF AUTHOR_NAME !== '' -->, оставленное пользователем {AUTHOR_NAME}<!-- ENDIF -->. Новые уведомления не будут приходить, пока вы не просмотрите тему.*/
/* */
/* Если вы хотите просмотреть самое новое сообщение с момента вашего последнего посещения, перейдите по следующей ссылке:*/
/* {U_NEWEST_POST}*/
/* */
/* Если вы хотите просмотреть всю тему, перейдите по следующей ссылке:*/
/* {U_TOPIC}*/
/* */
/* Если вы хотите просмотреть форум, перейдите по следующей ссылке:*/
/* {U_FORUM}*/
/* */
/* Если вы больше не хотите следить за темой, то либо щёлкните по находящейся в ней ссылке «Отписаться от темы», либо перейдите по следующей ссылке:*/
/* {U_STOP_WATCHING_TOPIC}*/
/* */
/* {EMAIL_SIG}*/
/* */
