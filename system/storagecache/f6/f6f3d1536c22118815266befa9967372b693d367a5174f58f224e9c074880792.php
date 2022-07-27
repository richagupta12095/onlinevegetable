<?php

/* default/template/extension/module/special.twig */
class __TwigTemplate_c1b05b206cabace46084ccd9e09f2b41855f889cf244858425af5d53a43b7358 extends Twig_Template
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
        echo "<div class=\"pro-bg\">
<h2 class=\"allheading\">";
        // line 2
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h2><hr>

";
        // line 4
        $context["temp"] = 0;
        // line 5
        $context["setCol"] = 2;
        // line 6
        echo "<div class=\"specialpro pro-nepr\">
  <div id=\"specialpr\" class=\"owl-theme owl-carousel\">
  ";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["products"]) ? $context["products"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 9
            echo "        ";
            $context["temp"] = ((isset($context["temp"]) ? $context["temp"] : null) + 1);
            // line 10
            echo "
        ";
            // line 11
            if ((((isset($context["temp"]) ? $context["temp"] : null) % (isset($context["setCol"]) ? $context["setCol"] : null)) == 1)) {
                // line 12
                echo "            <div class=\"multi-row\">
        ";
            }
            // line 14
            echo "<!-- writ code Here -->
  <div class=\"product-layout\">
    <div class=\"product-thumb transition\">
      <div class=\"image col-xs-4\"><a href=\"";
            // line 17
            echo $this->getAttribute($context["product"], "href", array());
            echo "\"><img src=\"";
            echo $this->getAttribute($context["product"], "thumb", array());
            echo "\" alt=\"";
            echo $this->getAttribute($context["product"], "name", array());
            echo "\" title=\"";
            echo $this->getAttribute($context["product"], "name", array());
            echo "\" class=\"img-responsive center-block\" /></a>
      <!-- inspire Images Start -->
              ";
            // line 19
            $context["t"] = 0;
            // line 20
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["product"], "insp_images", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["insp_image"]) {
                // line 21
                echo "                ";
                if (((isset($context["t"]) ? $context["t"] : null) == 0)) {
                    // line 22
                    echo "                 <a href=\"";
                    echo $this->getAttribute($context["product"], "href", array());
                    echo "\"><img src=\"";
                    echo $this->getAttribute($context["insp_image"], "popup", array());
                    echo "\" class=\"img-responsive second-img\" alt=\"hover image\"/></a>
                ";
                    // line 23
                    $context["t"] = ((isset($context["t"]) ? $context["t"] : null) + 1);
                    // line 24
                    echo "                ";
                }
                // line 25
                echo "              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['insp_image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 26
            echo "      <!-- inspire Images End -->
      </div>
      <div class=\"col-xs-8\">
      <div class=\"caption text-left\">
        
        ";
            // line 32
            echo "        <h4><a href=\"";
            echo $this->getAttribute($context["product"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["product"], "name", array());
            echo "</a></h4>
        ";
            // line 33
            if ($this->getAttribute($context["product"], "price", array())) {
                // line 34
                echo "        <p class=\"price\"> ";
                if ( !$this->getAttribute($context["product"], "special", array())) {
                    // line 35
                    echo "          ";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "
          ";
                } else {
                    // line 36
                    echo " <span class=\"price-new\">";
                    echo $this->getAttribute($context["product"], "special", array());
                    echo "</span> <span class=\"price-old\">";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "</span> ";
                }
                // line 37
                echo "         ";
                echo "</p>
        ";
            }
            // line 38
            echo " 
        
        ";
            // line 40
            if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                echo "     
          <input type=\"hidden\" name=\"qty\" id=\"qty_";
                // line 41
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\" size=\"1\" value=\"";
                echo $this->getAttribute($context["product"], "minimum", array());
                echo "\" >
        <input type=\"button\" id=\"decrease\"  class =\"decrease minus\" value=\"-\"  data-id=\"";
                // line 42
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\"/>
     
        <input type=\"text\" name=\"qty\" id=\"quantity_";
                // line 44
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\" size=\"1\" value=\"";
                echo $this->getAttribute($context["product"], "minimum", array());
                echo "\" readonly >
        <input type=\"button\" id=\"increase\" class=\"increase plus \" data-id=\"";
                // line 45
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\" value=\"+\" />
        ";
            }
            // line 47
            echo "        <div class=\"clearfix\"></div><br/><br/>
        ";
            // line 48
            if ($this->getAttribute($context["product"], "rating", array())) {
                // line 49
                echo "          <div class=\"rating\">
            ";
                // line 50
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 51
                    echo "            ";
                    if (($this->getAttribute($context["product"], "rating", array()) < $context["i"])) {
                        // line 52
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    } else {
                        // line 56
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    }
                    // line 60
                    echo "          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                echo "</div>";
            } else {
                // line 61
                echo "          <div class=\"rating\">";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 62
                    echo "          <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 63
                echo "</div>
        ";
            }
            // line 65
            echo "        </div>
     ";
            // line 77
            echo "    </div>
  </div>
</div>
  <!-- writ code Here End -->
 ";
            // line 81
            if ((((isset($context["temp"]) ? $context["temp"] : null) % (isset($context["setCol"]) ? $context["setCol"] : null)) == 0)) {
                // line 82
                echo "            </div>
        ";
            }
            // line 84
            echo "  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        echo " 

   ";
        // line 86
        if (((twig_length_filter($this->env, (isset($context["products"]) ? $context["products"] : null)) % (isset($context["setCol"]) ? $context["setCol"] : null)) != 0)) {
            // line 87
            echo "    <!-- <h1>hii</h1> -->
        </div>
    ";
        }
        // line 90
        echo "
</div>
</div>
</div>

<script type=\"text/javascript\">
    \$(document).ready(function() {
    \$(\"#specialpr\").owlCarousel({
    itemsCustom : [
    [0, 1],
    [375, 1],
    [600, 2],
    [992, 2],
    [1200, 3]
    ],
      // autoPlay: 1000,
      navigationText: ['<i class=\"fa fa-angle-left\" aria-hidden=\"true\"></i>', '<i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i>'],
      navigation : true,
      pagination:false
    });
    });
</script>";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/special.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  244 => 90,  239 => 87,  237 => 86,  228 => 84,  224 => 82,  222 => 81,  216 => 77,  213 => 65,  209 => 63,  202 => 62,  197 => 61,  189 => 60,  183 => 56,  177 => 52,  174 => 51,  170 => 50,  167 => 49,  165 => 48,  162 => 47,  157 => 45,  151 => 44,  146 => 42,  140 => 41,  136 => 40,  132 => 38,  127 => 37,  120 => 36,  114 => 35,  111 => 34,  109 => 33,  102 => 32,  95 => 26,  89 => 25,  86 => 24,  84 => 23,  77 => 22,  74 => 21,  69 => 20,  67 => 19,  56 => 17,  51 => 14,  47 => 12,  45 => 11,  42 => 10,  39 => 9,  35 => 8,  31 => 6,  29 => 5,  27 => 4,  22 => 2,  19 => 1,);
    }
}
/* <div class="pro-bg">*/
/* <h2 class="allheading">{{ heading_title }}</h2><hr>*/
/* */
/* {% set temp = 0 %}*/
/* {% set setCol = 2 %}*/
/* <div class="specialpro pro-nepr">*/
/*   <div id="specialpr" class="owl-theme owl-carousel">*/
/*   {% for product in products %}*/
/*         {% set temp = temp + 1 %}*/
/* */
/*         {% if temp % setCol == 1 %}*/
/*             <div class="multi-row">*/
/*         {% endif %}*/
/* <!-- writ code Here -->*/
/*   <div class="product-layout">*/
/*     <div class="product-thumb transition">*/
/*       <div class="image col-xs-4"><a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-responsive center-block" /></a>*/
/*       <!-- inspire Images Start -->*/
/*               {% set t = 0 %}*/
/*                 {% for insp_image in product.insp_images %}*/
/*                 {% if t == 0 %}*/
/*                  <a href="{{ product.href }}"><img src="{{ insp_image.popup }}" class="img-responsive second-img" alt="hover image"/></a>*/
/*                 {% set t = t + 1 %}*/
/*                 {% endif %}*/
/*               {% endfor %}*/
/*       <!-- inspire Images End -->*/
/*       </div>*/
/*       <div class="col-xs-8">*/
/*       <div class="caption text-left">*/
/*         */
/*         {# <p>{{ product.description }}</p> #}*/
/*         <h4><a href="{{ product.href }}">{{ product.name }}</a></h4>*/
/*         {% if product.price %}*/
/*         <p class="price"> {% if not product.special %}*/
/*           {{ product.price }}*/
/*           {% else %} <span class="price-new">{{ product.special }}</span> <span class="price-old">{{ product.price }}</span> {% endif %}*/
/*          {#  {% if product.tax %} <span class="price-tax">{{ text_tax }} {{ product.tax }}</span> {% endif %}  #}</p>*/
/*         {% endif %} */
/*         */
/*         {% if product.quantity > 0 %}     */
/*           <input type="hidden" name="qty" id="qty_{{product.product_id}}" size="1" value="{{product.minimum}}" >*/
/*         <input type="button" id="decrease"  class ="decrease minus" value="-"  data-id="{{product.product_id}}"/>*/
/*      */
/*         <input type="text" name="qty" id="quantity_{{product.product_id}}" size="1" value="{{product.minimum}}" readonly >*/
/*         <input type="button" id="increase" class="increase plus " data-id="{{product.product_id}}" value="+" />*/
/*         {% endif %}*/
/*         <div class="clearfix"></div><br/><br/>*/
/*         {% if product.rating %}*/
/*           <div class="rating">*/
/*             {% for i in 1..5 %}*/
/*             {% if product.rating < i %}*/
/*             <span class="fa fa-stack">*/
/*               <i class="fa fa-star-o fa-stack-2x"></i>*/
/*             </span>*/
/*             {% else %}*/
/*             <span class="fa fa-stack">*/
/*               <i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i>*/
/*             </span>*/
/*             {% endif %}*/
/*           {% endfor %}</div>{% else %}*/
/*           <div class="rating">{% for i in 1..5 %}*/
/*           <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>*/
/*           {% endfor %}</div>*/
/*         {% endif %}*/
/*         </div>*/
/*      {# <div class="button-group">*/
/*       {% if product.stock_status == 'Out Of Stock' %} */
/*         <button type="button" class="btn btn-danger"><span>Out of Stock</span></button> */
/*          {% else %}*/
/*         <button type="button" onclick="cart.add('{{ product.product_id }}');" class="acart"><svg><use xlink:href="#cart" /></svg>  */
/*           <span class="hidden-xs">{{ button_cart }}</span>*/
/*         </button>*/
/*         {% endif %}*/
/*         <button type="button" data-toggle="tooltip" title="{{ button_wishlist }}" onclick="wishlist.add('{{ product.product_id }}');" class="wishcom"><i class="fa fa-heart-o"></i><span class="hidden-xs">{{ button_wishlist }}</span></button>*/
/*         <button type="button" data-toggle="tooltip" title="{{ button_compare }}" onclick="compare.add('{{ product.product_id }}');" class="wishcom"><i class="fa fa-compress"></i><span class="hidden-xs">{{ button_compare }}</span></button>*/
/*       </div> #}*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/*   <!-- writ code Here End -->*/
/*  {% if temp % setCol == 0 %}*/
/*             </div>*/
/*         {% endif %}*/
/*   {% endfor %} */
/* */
/*    {% if products|length % setCol != 0 %}*/
/*     <!-- <h1>hii</h1> -->*/
/*         </div>*/
/*     {% endif %}*/
/* */
/* </div>*/
/* </div>*/
/* </div>*/
/* */
/* <script type="text/javascript">*/
/*     $(document).ready(function() {*/
/*     $("#specialpr").owlCarousel({*/
/*     itemsCustom : [*/
/*     [0, 1],*/
/*     [375, 1],*/
/*     [600, 2],*/
/*     [992, 2],*/
/*     [1200, 3]*/
/*     ],*/
/*       // autoPlay: 1000,*/
/*       navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],*/
/*       navigation : true,*/
/*       pagination:false*/
/*     });*/
/*     });*/
/* </script>*/
