<?php

/* default/template/extension/module/offerbanner.twig */
class __TwigTemplate_33cc017ee5cc971abac2c8c41b2cfb79c27ec057e66f2d2496b90282a02e913f extends Twig_Template
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
\t<div class=\"row\">
\t\t\t";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["banners"]) ? $context["banners"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["banner"]) {
            // line 4
            echo "\t\t\t\t<div class=\"col-sm-12 offerbanner\">
\t\t\t\t<div class=\" beffect\">
\t\t\t\t  ";
            // line 6
            if ($this->getAttribute($context["banner"], "link", array())) {
                // line 7
                echo "\t\t\t\t  \t<a href=\"";
                echo $this->getAttribute($context["banner"], "link", array());
                echo "\">
\t\t\t\t  \t\t<img src=\"";
                // line 8
                echo $this->getAttribute($context["banner"], "image", array());
                echo "\" alt=\"";
                echo $this->getAttribute($context["banner"], "title", array());
                echo "\" class=\"img-responsive\" />
\t\t\t\t  \t</a>
\t\t\t\t  ";
            } else {
                // line 11
                echo "\t\t\t\t  \t<img src=\"";
                echo $this->getAttribute($context["banner"], "image", array());
                echo "\" alt=\"";
                echo $this->getAttribute($context["banner"], "title", array());
                echo "\" class=\"img-responsive\" />";
            }
            // line 12
            echo "\t\t\t\t</div>
\t\t    </div>
\t\t\t  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['banner'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        echo "\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/offerbanner.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 15,  53 => 12,  46 => 11,  38 => 8,  33 => 7,  31 => 6,  27 => 4,  23 => 3,  19 => 1,);
    }
}
/* <div class="hidden-xs">*/
/* 	<div class="row">*/
/* 			{% for banner in banners %}*/
/* 				<div class="col-sm-12 offerbanner">*/
/* 				<div class=" beffect">*/
/* 				  {% if banner.link %}*/
/* 				  	<a href="{{ banner.link }}">*/
/* 				  		<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />*/
/* 				  	</a>*/
/* 				  {% else %}*/
/* 				  	<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />{% endif %}*/
/* 				</div>*/
/* 		    </div>*/
/* 			  {% endfor %}*/
/* 	</div>*/
/* </div>*/
