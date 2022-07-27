<?php

/* default/template/extension/module/category.twig */
class __TwigTemplate_302fc934def90c834094ff6007846c0e29aa4cbc8df9461e33f391e9437c8805 extends Twig_Template
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
        echo "<div class=\"hidden-xs\">
";
        // line 2
        if ((isset($context["categories"]) ? $context["categories"] : null)) {
            // line 3
            echo "<div class=\"left-heading\">";
            echo (isset($context["category_title"]) ? $context["category_title"] : null);
            echo "</div>
<div class=\"cate-menu \">
  <nav id=\"menu\" class=\"navbar\">
    <div class=\"navbar-header\"><span id=\"category\" class=\"visible-xs\">";
            // line 6
            echo (isset($context["text_category"]) ? $context["text_category"] : null);
            echo "</span>
      <button type=\"button\" class=\"btn btn-navbar navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\"><i class=\"fa fa-bars\"></i></button>
    </div>
    <div class=\"collapse navbar-collapse navbar-ex1-collapse\">
      <ul class=\"nav\">
        ";
            // line 11
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                // line 12
                echo "        ";
                if ($this->getAttribute($context["category"], "children", array())) {
                    // line 13
                    echo "        <li class=\"dropdown\"><a href=\"";
                    echo $this->getAttribute($context["category"], "href", array());
                    echo "\" class=\"dropdown-toggle header-menu\" data-toggle=\"dropdown\">";
                    echo "<img src=\"image/catalog/menui.png\" /> ";
                    echo $this->getAttribute($context["category"], "name", array());
                    echo "<i class=\"fa fa-angle-down enangle\"></i></a>
          <div class=\"dropdown-menu\">
            <div class=\"dropdown-inner\">
            ";
                    // line 16
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_array_batch($this->getAttribute($context["category"], "children", array()), (twig_length_filter($this->env, $this->getAttribute($context["category"], "children", array())) / twig_round($this->getAttribute($context["category"], "column", array()), 1, "ceil"))));
                    foreach ($context['_seq'] as $context["_key"] => $context["children"]) {
                        // line 17
                        echo "              <ul class=\"list-unstyled\">
                ";
                        // line 18
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($context["children"]);
                        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                            // line 19
                            echo "                 <!--3rd level-->
                    <li class=\"dropdown-submenu\"> <a href=\"";
                            // line 20
                            echo $this->getAttribute($context["child"], "href", array());
                            echo "\" class=\"submenu-title\"> ";
                            echo $this->getAttribute($context["child"], "name", array());
                            echo " </a>
                      ";
                            // line 21
                            if ($this->getAttribute($context["child"], "grand_childs", array())) {
                                // line 22
                                echo "                      <ul class=\"list-unstyled grand-child\">
                        ";
                                // line 23
                                $context['_parent'] = $context;
                                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["child"], "grand_childs", array()));
                                foreach ($context['_seq'] as $context["_key"] => $context["grand_child"]) {
                                    // line 24
                                    echo "                        <li> <a href=\"";
                                    echo $this->getAttribute($context["grand_child"], "href", array());
                                    echo "\"> ";
                                    echo $this->getAttribute($context["grand_child"], "name", array());
                                    echo " </a> </li>
                        ";
                                }
                                $_parent = $context['_parent'];
                                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['grand_child'], $context['_parent'], $context['loop']);
                                $context = array_intersect_key($context, $_parent) + $_parent;
                                // line 26
                                echo "                      </ul>
                      ";
                            }
                            // line 28
                            echo "                    </li>
                    <!--3rd level over-->
                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 31
                        echo "              </ul>
              ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 32
                    echo "</div>
            <!-- <a href=\"";
                    // line 33
                    echo $this->getAttribute($context["category"], "href", array());
                    echo "\" class=\"see-all\">";
                    echo (isset($context["text_all"]) ? $context["text_all"] : null);
                    echo " ";
                    echo $this->getAttribute($context["category"], "name", array());
                    echo "</a> --> </div>
        </li>
        ";
                } else {
                    // line 36
                    echo "        <li><a href=\"";
                    echo $this->getAttribute($context["category"], "href", array());
                    echo "\">";
                    echo "<img src=\"image/catalog/menui.png\" /> ";
                    echo $this->getAttribute($context["category"], "name", array());
                    echo "</a></li>
        ";
                }
                // line 38
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "      </ul>
    </div>
  </nav>
</div>
";
        }
        // line 44
        echo "</div>

<script type=\"text/javascript\">
 function headermenu() {
     if (jQuery(window).width() < 992)
     {
         jQuery('ul.nav li.dropdown a.header-menu').attr(\"data-toggle\",\"dropdown\");        
     }
     else
     {
         jQuery('ul.nav li.dropdown a.header-menu').attr(\"data-toggle\",\"\"); 
     }
}
\$(document).ready(function(){headermenu();});
jQuery(window).resize(function() {headermenu();});
jQuery(window).scroll(function() {headermenu();});
</script>";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/category.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 44,  143 => 39,  137 => 38,  128 => 36,  118 => 33,  115 => 32,  108 => 31,  100 => 28,  96 => 26,  85 => 24,  81 => 23,  78 => 22,  76 => 21,  70 => 20,  67 => 19,  63 => 18,  60 => 17,  56 => 16,  46 => 13,  43 => 12,  39 => 11,  31 => 6,  24 => 3,  22 => 2,  19 => 1,);
    }
}
/* <div class="hidden-xs">*/
/* {% if categories %}*/
/* <div class="left-heading">{{ category_title }}</div>*/
/* <div class="cate-menu ">*/
/*   <nav id="menu" class="navbar">*/
/*     <div class="navbar-header"><span id="category" class="visible-xs">{{ text_category }}</span>*/
/*       <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>*/
/*     </div>*/
/*     <div class="collapse navbar-collapse navbar-ex1-collapse">*/
/*       <ul class="nav">*/
/*         {% for category in categories %}*/
/*         {% if category.children %}*/
/*         <li class="dropdown"><a href="{{ category.href }}" class="dropdown-toggle header-menu" data-toggle="dropdown">{# <svg width="13px" height="13px"><use xlink:href="#cart" /></svg> #}<img src="image/catalog/menui.png" /> {{ category.name }}<i class="fa fa-angle-down enangle"></i></a>*/
/*           <div class="dropdown-menu">*/
/*             <div class="dropdown-inner">*/
/*             {% for children in category.children|batch(category.children|length / category.column|round(1, 'ceil')) %}*/
/*               <ul class="list-unstyled">*/
/*                 {% for child in children %}*/
/*                  <!--3rd level-->*/
/*                     <li class="dropdown-submenu"> <a href="{{ child.href }}" class="submenu-title"> {{ child.name }} </a>*/
/*                       {% if child.grand_childs %}*/
/*                       <ul class="list-unstyled grand-child">*/
/*                         {% for grand_child in child.grand_childs %}*/
/*                         <li> <a href="{{ grand_child.href }}"> {{grand_child.name}} </a> </li>*/
/*                         {% endfor %}*/
/*                       </ul>*/
/*                       {% endif %}*/
/*                     </li>*/
/*                     <!--3rd level over-->*/
/*                 {% endfor %}*/
/*               </ul>*/
/*               {% endfor %}</div>*/
/*             <!-- <a href="{{ category.href }}" class="see-all">{{ text_all }} {{ category.name }}</a> --> </div>*/
/*         </li>*/
/*         {% else %}*/
/*         <li><a href="{{ category.href }}">{# <svg width="13px" height="13px"><use xlink:href="#cart" /></svg> #}<img src="image/catalog/menui.png" /> {{ category.name }}</a></li>*/
/*         {% endif %}*/
/*         {% endfor %}*/
/*       </ul>*/
/*     </div>*/
/*   </nav>*/
/* </div>*/
/* {% endif %}*/
/* </div>*/
/* */
/* <script type="text/javascript">*/
/*  function headermenu() {*/
/*      if (jQuery(window).width() < 992)*/
/*      {*/
/*          jQuery('ul.nav li.dropdown a.header-menu').attr("data-toggle","dropdown");        */
/*      }*/
/*      else*/
/*      {*/
/*          jQuery('ul.nav li.dropdown a.header-menu').attr("data-toggle",""); */
/*      }*/
/* }*/
/* $(document).ready(function(){headermenu();});*/
/* jQuery(window).resize(function() {headermenu();});*/
/* jQuery(window).scroll(function() {headermenu();});*/
/* </script>*/
