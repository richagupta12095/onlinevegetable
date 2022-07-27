<?php

/* default/template/extension/module/sellbanner.twig */
class __TwigTemplate_e34ffc9e08519aa0fa4cf2df5a9157509abbc463d8ce8a94ce87135875ee5642 extends Twig_Template
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
        echo "<div class=\"sellbanner col-xs-12\">
\t<div class=\"row\">
\t\t\t";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["banners"]) ? $context["banners"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["banner"]) {
            // line 4
            echo "\t\t\t\t<div class=\"col-xs-12 b-effect-p\">
\t\t\t\t<div class=\"img-effect-p\">
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
                echo "\" class=\"img-responsive center-block\" />
\t\t\t\t  \t</a>
\t\t\t\t  ";
            } else {
                // line 11
                echo "\t\t\t\t  \t<img src=\"";
                echo $this->getAttribute($context["banner"], "image", array());
                echo "\" alt=\"";
                echo $this->getAttribute($context["banner"], "title", array());
                echo "\" class=\"img-responsive center-block\" />";
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
        return "default/template/extension/module/sellbanner.twig";
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
/* <div class="sellbanner col-xs-12">*/
/* 	<div class="row">*/
/* 			{% for banner in banners %}*/
/* 				<div class="col-xs-12 b-effect-p">*/
/* 				<div class="img-effect-p">*/
/* 				  {% if banner.link %}*/
/* 				  	<a href="{{ banner.link }}">*/
/* 				  		<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive center-block" />*/
/* 				  	</a>*/
/* 				  {% else %}*/
/* 				  	<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive center-block" />{% endif %}*/
/* 				</div>*/
/* 		    </div>*/
/* 			  {% endfor %}*/
/* 	</div>*/
/* </div>*/
