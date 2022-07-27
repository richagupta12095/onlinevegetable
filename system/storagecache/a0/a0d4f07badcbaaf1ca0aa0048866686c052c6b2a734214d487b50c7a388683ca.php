<?php

/* default/template/extension/module/banner.twig */
class __TwigTemplate_2add056443a57131a3968d348eb301cff4a78bd4ac054c7ded6df6148314d37a extends Twig_Template
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
        echo "<div class=\"fbanner col-xs-12\">
<div class=\"row\">
  <div id=\"banner";
        // line 3
        echo (isset($context["module"]) ? $context["module"] : null);
        echo "\">
    ";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["banners"]) ? $context["banners"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["banner"]) {
            // line 5
            echo "     \t";
            if ((twig_length_filter($this->env, (isset($context["banners"]) ? $context["banners"] : null)) > 1)) {
                // line 6
                echo "     \t\t<div class=\"col-xs-6\">";
                if ($this->getAttribute($context["banner"], "link", array())) {
                    echo "<a href=\"";
                    echo $this->getAttribute($context["banner"], "link", array());
                    echo "\"><img src=\"";
                    echo $this->getAttribute($context["banner"], "image", array());
                    echo "\" alt=\"";
                    echo $this->getAttribute($context["banner"], "title", array());
                    echo "\" class=\"img-responsive\" /></a>";
                } else {
                    echo "<img src=\"";
                    echo $this->getAttribute($context["banner"], "image", array());
                    echo "\" alt=\"";
                    echo $this->getAttribute($context["banner"], "title", array());
                    echo "\" class=\"img-responsive\" />";
                }
                echo "</div>
     \t";
            } else {
                // line 8
                echo "     \t\t<div class=\"col-xs-12\">";
                if ($this->getAttribute($context["banner"], "link", array())) {
                    echo "<a href=\"";
                    echo $this->getAttribute($context["banner"], "link", array());
                    echo "\"><img src=\"";
                    echo $this->getAttribute($context["banner"], "image", array());
                    echo "\" alt=\"";
                    echo $this->getAttribute($context["banner"], "title", array());
                    echo "\" class=\"img-responsive\" /></a>";
                } else {
                    echo "<img src=\"";
                    echo $this->getAttribute($context["banner"], "image", array());
                    echo "\" alt=\"";
                    echo $this->getAttribute($context["banner"], "title", array());
                    echo "\" class=\"img-responsive\" />";
                }
                echo "</div>
     \t";
            }
            // line 9
            echo "      
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['banner'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 11
        echo "  </div>
</div>
</div>";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/banner.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 11,  74 => 9,  54 => 8,  34 => 6,  31 => 5,  27 => 4,  23 => 3,  19 => 1,);
    }
}
/* <div class="fbanner col-xs-12">*/
/* <div class="row">*/
/*   <div id="banner{{ module }}">*/
/*     {% for banner in banners %}*/
/*      	{% if banners|length > 1  %}*/
/*      		<div class="col-xs-6">{% if banner.link %}<a href="{{ banner.link }}"><img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" /></a>{% else %}<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />{% endif %}</div>*/
/*      	{% else %}*/
/*      		<div class="col-xs-12">{% if banner.link %}<a href="{{ banner.link }}"><img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" /></a>{% else %}<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />{% endif %}</div>*/
/*      	{% endif %}      */
/*       {% endfor %}*/
/*   </div>*/
/* </div>*/
/* </div>*/
