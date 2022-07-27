<?php

/* default/template/extension/module/slideshow.twig */
class __TwigTemplate_78774c21b42516b5375d4356671b86e998c519026983503d20397204cbb169df extends Twig_Template
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
        echo "
  <div id=\"slideshow";
        // line 2
        echo (isset($context["module"]) ? $context["module"] : null);
        echo "\" class=\"owl-carousel owl-theme\">
   ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["banners"]) ? $context["banners"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["banner"]) {
            // line 4
            echo "      <div class=\"text-center\">";
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
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['banner'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 6
        echo "  </div>



<script type=\"text/javascript\">
    \$(document).ready(function() {
    \$(\"#slideshow";
        // line 12
        echo (isset($context["module"]) ? $context["module"] : null);
        echo "\").owlCarousel({
    itemsCustom : [
    [0, 1]
    ],
      autoPlay: 2500,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      loop: true,
      navigationText: ['<i class=\"fa fa-angle-left\" aria-hidden=\"true\"></i>', '<i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i>'],
      navigation : false,
      pagination:true
    });
    });
</script>";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/slideshow.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 12,  53 => 6,  30 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }
}
/* */
/*   <div id="slideshow{{ module }}" class="owl-carousel owl-theme">*/
/*    {% for banner in banners %}*/
/*       <div class="text-center">{% if banner.link %}<a href="{{ banner.link }}"><img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" /></a>{% else %}<img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />{% endif %}</div>*/
/*       {% endfor %}*/
/*   </div>*/
/* */
/* */
/* */
/* <script type="text/javascript">*/
/*     $(document).ready(function() {*/
/*     $("#slideshow{{ module }}").owlCarousel({*/
/*     itemsCustom : [*/
/*     [0, 1]*/
/*     ],*/
/*       autoPlay: 2500,*/
/*       animateIn: 'fadeIn',*/
/*       animateOut: 'fadeOut',*/
/*       loop: true,*/
/*       navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],*/
/*       navigation : false,*/
/*       pagination:true*/
/*     });*/
/*     });*/
/* </script>*/
