<?php

/* ucp_notifications.html */
class __TwigTemplate_038450558848b02d09a32c8ab7756566a0826719b38c7f56a8290d7018ed4c52 extends Twig_Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_header.html", "ucp_notifications.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\"";
        echo (isset($context["S_FORM_ENCTYPE"]) ? $context["S_FORM_ENCTYPE"] : null);
        echo ">

<h2>";
        // line 5
        echo (isset($context["TITLE"]) ? $context["TITLE"] : null);
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">

\t\t<p>";
        // line 9
        echo (isset($context["TITLE_EXPLAIN"]) ? $context["TITLE_EXPLAIN"] : null);
        echo "</p>

\t\t";
        // line 11
        if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
            // line 12
            echo "\t\t\t<table class=\"table1\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>";
            // line 15
            echo $this->env->getExtension('phpbb')->lang("NOTIFICATION_TYPE");
            echo "</th>
\t\t\t\t\t\t";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_methods", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                // line 17
                echo "\t\t\t\t\t\t\t<th class=\"mark\">";
                echo $this->getAttribute($context["notification_methods"], "NAME", array());
                echo "</th>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            echo "\t\t\t\t\t\t<th class=\"mark\">";
            echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
            echo "</th>
\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t";
            // line 23
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_types"]) {
                // line 24
                echo "\t\t\t\t\t";
                if ($this->getAttribute($context["notification_types"], "GROUP_NAME", array())) {
                    // line 25
                    echo "\t\t\t\t\t\t<tr class=\"bg3\">
\t\t\t\t\t\t\t<td colspan=\"";
                    // line 26
                    echo (isset($context["NOTIFICATION_TYPES_COLS"]) ? $context["NOTIFICATION_TYPES_COLS"] : null);
                    echo "\">";
                    echo $this->getAttribute($context["notification_types"], "GROUP_NAME", array());
                    echo "</td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                } else {
                    // line 29
                    echo "\t\t\t\t\t\t<tr class=\"";
                    if (($this->getAttribute($context["notification_types"], "S_ROW_COUNT", array()) % 2 == 1)) {
                        echo "bg1";
                    } else {
                        echo "bg2";
                    }
                    echo "\">
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t";
                    // line 31
                    echo $this->getAttribute($context["notification_types"], "NAME", array());
                    echo "
\t\t\t\t\t\t\t\t";
                    // line 32
                    if ($this->getAttribute($context["notification_types"], "EXPLAIN", array())) {
                        echo "<br />&nbsp; &nbsp;";
                        echo $this->getAttribute($context["notification_types"], "EXPLAIN", array());
                    }
                    // line 33
                    echo "\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t";
                    // line 34
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["notification_types"], "notification_methods", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                        // line 35
                        echo "\t\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                        echo $this->getAttribute($context["notification_types"], "TYPE", array());
                        echo "_";
                        echo $this->getAttribute($context["notification_methods"], "METHOD", array());
                        echo "\"";
                        if ($this->getAttribute($context["notification_methods"], "SUBSCRIBED", array())) {
                            echo " checked=\"checked\"";
                        }
                        echo " /></td>
\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 37
                    echo "\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                    echo $this->getAttribute($context["notification_types"], "TYPE", array());
                    echo "_notification\"";
                    if ($this->getAttribute($context["notification_types"], "SUBSCRIBED", array())) {
                        echo " checked=\"checked\"";
                    }
                    echo " /></td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                }
                // line 40
                echo "\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_types'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 41
            echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t";
        } else {
            // line 44
            echo "\t\t\t";
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array()))) {
                // line 45
                echo "\t\t\t\t<div class=\"action-bar top\">
\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t";
                // line 47
                if ((isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null)) {
                    echo "<a href=\"";
                    echo (isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null);
                    echo "\" class=\"mark\">";
                    echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS_MARK_ALL_READ");
                    echo "</a> &bull; ";
                }
                // line 48
                echo "\t\t\t\t\t\t";
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t\t";
                // line 49
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                    // line 50
                    echo "\t\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 50)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 51
                    echo "\t\t\t\t\t\t";
                } else {
                    // line 52
                    echo "\t\t\t\t\t\t\t &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t\t";
                }
                // line 54
                echo "\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t<div class=\"notification_list\">
\t\t\t\t<ul class=\"topiclist two-columns\">
\t\t\t\t\t<li class=\"header\">
\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 61
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo "</div></dt>
\t\t\t\t\t\t\t<dd class=\"mark\">";
                // line 62
                echo $this->env->getExtension('phpbb')->lang("MARK_READ");
                echo "</dd>
\t\t\t\t\t\t</dl>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t\t<ul class=\"topiclist cplist two-columns\">
\t\t\t\t\t";
                // line 67
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["notification_list"]) {
                    // line 68
                    echo "\t\t\t\t\t\t<li class=\"row";
                    if ($this->getAttribute($context["notification_list"], "UNREAD", array())) {
                        echo " bg3";
                    } else {
                        if (($this->getAttribute($context["notification_list"], "S_ROW_COUNT", array()) % 2 == 1)) {
                            echo " bg1";
                        } else {
                            echo " bg2";
                        }
                    }
                    if ($this->getAttribute($context["notification_list"], "STYLING", array())) {
                        echo " ";
                        echo $this->getAttribute($context["notification_list"], "STYLING", array());
                    }
                    echo "\">
\t\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t\t<dt>
\t\t\t\t\t\t\t\t\t<div class=\"list-inner\">\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t\t";
                    // line 72
                    if ($this->getAttribute($context["notification_list"], "AVATAR", array())) {
                        echo $this->getAttribute($context["notification_list"], "AVATAR", array());
                    } else {
                        echo "<img src=\"";
                        echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
                        echo "/images/no_avatar.gif\" alt=\"\" />";
                    }
                    // line 73
                    echo "\t\t\t\t\t\t\t\t\t\t<div class=\"notifications\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 74
                    if ($this->getAttribute($context["notification_list"], "URL", array())) {
                        echo "<a href=\"";
                        if ($this->getAttribute($context["notification_list"], "UNREAD", array())) {
                            echo $this->getAttribute($context["notification_list"], "U_MARK_READ", array());
                        } else {
                            echo $this->getAttribute($context["notification_list"], "URL", array());
                        }
                        echo "\">";
                    }
                    // line 75
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_title\">";
                    echo $this->getAttribute($context["notification_list"], "FORMATTED_TITLE", array());
                    if ($this->getAttribute($context["notification_list"], "REFERENCE", array())) {
                        echo " ";
                        echo $this->getAttribute($context["notification_list"], "REFERENCE", array());
                    }
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 76
                    if ($this->getAttribute($context["notification_list"], "URL", array())) {
                        echo "</a>";
                    }
                    echo "\t\t\t\t
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 77
                    if ($this->getAttribute($context["notification_list"], "FORUM", array())) {
                        echo "<p class=\"notifications_forum\">";
                        echo $this->getAttribute($context["notification_list"], "FORUM", array());
                        echo "</p>";
                    }
                    // line 78
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if ($this->getAttribute($context["notification_list"], "REASON", array())) {
                        echo "<p class=\"notifications_reason\">";
                        echo $this->getAttribute($context["notification_list"], "REASON", array());
                        echo "</p>";
                    }
                    // line 79
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_time\">";
                    echo $this->getAttribute($context["notification_list"], "TIME", array());
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</dt>

\t\t\t\t\t\t\t\t<dd class=\"mark\">&nbsp;<input type=\"checkbox\" name=\"mark[]\" value=\"";
                    // line 84
                    echo $this->getAttribute($context["notification_list"], "NOTIFICATION_ID", array());
                    echo "\"";
                    if ( !$this->getAttribute($context["notification_list"], "UNREAD", array())) {
                        echo " disabled=\"disabled\"";
                    }
                    echo " /> <dfn>";
                    echo $this->env->getExtension('phpbb')->lang("MARK_READ");
                    echo "</dfn>&nbsp;</dd>
\t\t\t\t\t\t\t</dl>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_list'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 88
                echo "\t\t\t\t</ul>
\t\t\t</div>

\t\t\t<div class=\"action-bar bottom\">
\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t";
                // line 93
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t";
                // line 94
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                    // line 95
                    echo "\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 95)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 96
                    echo "\t\t\t\t\t";
                } else {
                    // line 97
                    echo "\t\t\t\t\t\t  &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t";
                }
                // line 99
                echo "\t\t\t\t</div>
\t\t\t</div>

\t\t\t";
            } else {
                // line 103
                echo "\t\t\t\t<p><strong>";
                echo $this->env->getExtension('phpbb')->lang("NO_NOTIFICATIONS");
                echo "</strong></p>
\t\t\t";
            }
            // line 105
            echo "
\t\t";
        }
        // line 107
        echo "\t</div>
</div>

";
        // line 110
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types", array())) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array())))) {
            // line 111
            echo "<fieldset class=\"display-actions\">
\t<input type=\"hidden\" name=\"form_time\" value=\"";
            // line 112
            echo (isset($context["FORM_TIME"]) ? $context["FORM_TIME"] : null);
            echo "\" />
\t";
            // line 113
            echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
            echo "
\t<input type=\"submit\" name=\"submit\" value=\"";
            // line 114
            if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
                echo $this->env->getExtension('phpbb')->lang("SUBMIT");
            } else {
                echo $this->env->getExtension('phpbb')->lang("MARK_READ");
            }
            echo "\" class=\"button1\" />
\t<div><a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', true); return false;\">";
            // line 115
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', false); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></div>
\t";
            // line 116
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
</fieldset>
";
        }
        // line 119
        echo "
</form>

";
        // line 122
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_footer.html", "ucp_notifications.html", 122)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_notifications.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  427 => 122,  422 => 119,  416 => 116,  410 => 115,  402 => 114,  398 => 113,  394 => 112,  391 => 111,  389 => 110,  384 => 107,  380 => 105,  374 => 103,  368 => 99,  362 => 97,  359 => 96,  346 => 95,  344 => 94,  338 => 93,  331 => 88,  315 => 84,  306 => 79,  299 => 78,  293 => 77,  287 => 76,  278 => 75,  268 => 74,  265 => 73,  257 => 72,  237 => 68,  233 => 67,  225 => 62,  221 => 61,  212 => 54,  206 => 52,  203 => 51,  190 => 50,  188 => 49,  181 => 48,  173 => 47,  169 => 45,  166 => 44,  161 => 41,  155 => 40,  144 => 37,  129 => 35,  125 => 34,  122 => 33,  117 => 32,  113 => 31,  103 => 29,  95 => 26,  92 => 25,  89 => 24,  85 => 23,  77 => 19,  68 => 17,  64 => 16,  60 => 15,  55 => 12,  53 => 11,  48 => 9,  41 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
/* <!-- INCLUDE ucp_header.html -->*/
/* */
/* <form id="ucp" method="post" action="{S_UCP_ACTION}"{S_FORM_ENCTYPE}>*/
/* */
/* <h2>{TITLE}</h2>*/
/* <div class="panel">*/
/* 	<div class="inner">*/
/* */
/* 		<p>{TITLE_EXPLAIN}</p>*/
/* */
/* 		<!-- IF MODE == 'notification_options' -->*/
/* 			<table class="table1">*/
/* 				<thead>*/
/* 					<tr>*/
/* 						<th>{L_NOTIFICATION_TYPE}</th>*/
/* 						<!-- BEGIN notification_methods -->*/
/* 							<th class="mark">{notification_methods.NAME}</th>*/
/* 						<!-- END notification_methods -->*/
/* 						<th class="mark">{L_NOTIFICATIONS}</th>*/
/* 					</tr>*/
/* 				</thead>*/
/* 				<tbody>*/
/* 				<!-- BEGIN notification_types -->*/
/* 					<!-- IF notification_types.GROUP_NAME -->*/
/* 						<tr class="bg3">*/
/* 							<td colspan="{NOTIFICATION_TYPES_COLS}">{notification_types.GROUP_NAME}</td>*/
/* 						</tr>*/
/* 					<!-- ELSE -->*/
/* 						<tr class="<!-- IF notification_types.S_ROW_COUNT is odd -->bg1<!-- ELSE -->bg2<!-- ENDIF -->">*/
/* 							<td>*/
/* 								{notification_types.NAME}*/
/* 								<!-- IF notification_types.EXPLAIN --><br />&nbsp; &nbsp;{notification_types.EXPLAIN}<!-- ENDIF -->*/
/* 							</td>*/
/* 							<!-- BEGIN notification_methods -->*/
/* 								<td class="mark"><input type="checkbox" name="{notification_types.TYPE}_{notification_methods.METHOD}"<!-- IF notification_methods.SUBSCRIBED --> checked="checked"<!-- ENDIF --> /></td>*/
/* 							<!-- END notification_methods -->*/
/* 							<td class="mark"><input type="checkbox" name="{notification_types.TYPE}_notification"<!-- IF notification_types.SUBSCRIBED --> checked="checked"<!-- ENDIF --> /></td>*/
/* 						</tr>*/
/* 					<!-- ENDIF -->*/
/* 				<!-- END notification_types -->*/
/* 				</tbody>*/
/* 			</table>*/
/* 		<!-- ELSE -->*/
/* 			<!-- IF .notification_list -->*/
/* 				<div class="action-bar top">*/
/* 					<div class="pagination">*/
/* 						<!-- IF U_MARK_ALL --><a href="{U_MARK_ALL}" class="mark">{L_NOTIFICATIONS_MARK_ALL_READ}</a> &bull; <!-- ENDIF -->*/
/* 						{L_NOTIFICATIONS} [<strong>{TOTAL_COUNT}</strong>]*/
/* 						<!-- IF .pagination -->*/
/* 							<!-- INCLUDE pagination.html -->*/
/* 						<!-- ELSE -->*/
/* 							 &bull; {PAGE_NUMBER}*/
/* 						<!-- ENDIF -->*/
/* 					</div>*/
/* 				</div>*/
/* */
/* 			<div class="notification_list">*/
/* 				<ul class="topiclist two-columns">*/
/* 					<li class="header">*/
/* 						<dl>*/
/* 							<dt><div class="list-inner">{L_NOTIFICATIONS}</div></dt>*/
/* 							<dd class="mark">{L_MARK_READ}</dd>*/
/* 						</dl>*/
/* 					</li>*/
/* 				</ul>*/
/* 				<ul class="topiclist cplist two-columns">*/
/* 					<!-- BEGIN notification_list -->*/
/* 						<li class="row<!-- IF notification_list.UNREAD --> bg3<!-- ELSE --><!-- IF notification_list.S_ROW_COUNT is odd --> bg1<!-- ELSE --> bg2<!-- ENDIF --><!-- ENDIF --><!-- IF notification_list.STYLING --> {notification_list.STYLING}<!-- ENDIF -->">*/
/* 							<dl>*/
/* 								<dt>*/
/* 									<div class="list-inner">										*/
/* 										<!-- IF notification_list.AVATAR -->{notification_list.AVATAR}<!-- ELSE --><img src="{T_THEME_PATH}/images/no_avatar.gif" alt="" /><!-- ENDIF -->*/
/* 										<div class="notifications">*/
/* 											<!-- IF notification_list.URL --><a href="<!-- IF notification_list.UNREAD -->{notification_list.U_MARK_READ}<!-- ELSE -->{notification_list.URL}<!-- ENDIF -->"><!-- ENDIF -->*/
/* 											<p class="notifications_title">{notification_list.FORMATTED_TITLE}<!-- IF notification_list.REFERENCE --> {notification_list.REFERENCE}<!-- ENDIF --></p>*/
/* 											<!-- IF notification_list.URL --></a><!-- ENDIF -->				*/
/* 											<!-- IF notification_list.FORUM --><p class="notifications_forum">{notification_list.FORUM}</p><!-- ENDIF -->*/
/* 											<!-- IF notification_list.REASON --><p class="notifications_reason">{notification_list.REASON}</p><!-- ENDIF -->*/
/* 											<p class="notifications_time">{notification_list.TIME}</p>*/
/* 										</div>*/
/* 									</div>*/
/* 								</dt>*/
/* */
/* 								<dd class="mark">&nbsp;<input type="checkbox" name="mark[]" value="{notification_list.NOTIFICATION_ID}"<!-- IF not notification_list.UNREAD --> disabled="disabled"<!-- ENDIF --> /> <dfn>{L_MARK_READ}</dfn>&nbsp;</dd>*/
/* 							</dl>*/
/* 						</li>*/
/* 					<!-- END notification_list -->*/
/* 				</ul>*/
/* 			</div>*/
/* */
/* 			<div class="action-bar bottom">*/
/* 				<div class="pagination">*/
/* 					{L_NOTIFICATIONS} [<strong>{TOTAL_COUNT}</strong>]*/
/* 					<!-- IF .pagination -->*/
/* 						<!-- INCLUDE pagination.html -->*/
/* 					<!-- ELSE -->*/
/* 						  &bull; {PAGE_NUMBER}*/
/* 					<!-- ENDIF -->*/
/* 				</div>*/
/* 			</div>*/
/* */
/* 			<!-- ELSE -->*/
/* 				<p><strong>{L_NO_NOTIFICATIONS}</strong></p>*/
/* 			<!-- ENDIF -->*/
/* */
/* 		<!-- ENDIF -->*/
/* 	</div>*/
/* </div>*/
/* */
/* <!-- IF .notification_types or .notification_list -->*/
/* <fieldset class="display-actions">*/
/* 	<input type="hidden" name="form_time" value="{FORM_TIME}" />*/
/* 	{S_HIDDEN_FIELDS}*/
/* 	<input type="submit" name="submit" value="<!-- IF MODE == 'notification_options' -->{L_SUBMIT}<!-- ELSE -->{L_MARK_READ}<!-- ENDIF -->" class="button1" />*/
/* 	<div><a href="#" onclick="$('#ucp input:checkbox').prop('checked', true); return false;">{L_MARK_ALL}</a> &bull; <a href="#" onclick="$('#ucp input:checkbox').prop('checked', false); return false;">{L_UNMARK_ALL}</a></div>*/
/* 	{S_FORM_TOKEN}*/
/* </fieldset>*/
/* <!-- ENDIF -->*/
/* */
/* </form>*/
/* */
/* <!-- INCLUDE ucp_footer.html -->*/
/* */
