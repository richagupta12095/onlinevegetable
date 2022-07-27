<?php

/* default/template/extension/module/bestproduct.twig */
class __TwigTemplate_3e6372e6315ed226ea6f15fb245a45e1d27bddcbbe421f68bff18521850fbab7 extends Twig_Template
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

<div class=\"pro-bg\">
<h2>";
        // line 4
        echo (isset($context["bestpro_title"]) ? $context["bestpro_title"] : null);
        echo "</h2><hr>
<div class=\"pro-nepr\">
  <div id=\"bestpro\" class=\"owl-theme owl-carousel\">
 ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["products"]) ? $context["products"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 8
            echo "  <div class=\"product-layout\">
    <div class=\"product-thumb transition cart_div_";
            // line 9
            echo $this->getAttribute($context["product"], "product_id", array());
            echo " ";
            if (($this->getAttribute($context["product"], "background", array()) == "yes")) {
                echo " selected-product ";
            }
            echo "\">
      <div class=\"image alert_";
            // line 10
            echo $this->getAttribute($context["product"], "product_id", array());
            echo "\"><a href=\"";
            echo $this->getAttribute($context["product"], "href", array());
            echo "\"><img src=\"";
            echo $this->getAttribute($context["product"], "thumb", array());
            echo "\" alt=\"";
            echo $this->getAttribute($context["product"], "name", array());
            echo "\" title=\"";
            echo $this->getAttribute($context["product"], "name", array());
            echo "\" class=\"img-responsive center-block\" /></a>
          <!-- insp Images Start -->
                 ";
            // line 12
            $context["t"] = 0;
            // line 13
            echo "                  ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["product"], "more_images", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["more_image"]) {
                // line 14
                echo "                  ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["more_image"]);
                foreach ($context['_seq'] as $context["_key"] => $context["pop"]) {
                    // line 15
                    echo "                  ";
                    if (((isset($context["t"]) ? $context["t"] : null) == 0)) {
                        // line 16
                        echo "                    <a href=\"";
                        echo $this->getAttribute($context["product"], "href", array());
                        echo "\"><img src=\"";
                        echo $this->getAttribute($context["pop"], "popup_more", array());
                        echo "\" class=\"img-responsive second-img\" alt=\"hover image\"/></a>
                  ";
                        // line 17
                        $context["t"] = ((isset($context["t"]) ? $context["t"] : null) + 1);
                        // line 18
                        echo "                    ";
                    }
                    // line 19
                    echo "
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pop'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 21
                echo "                  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['more_image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 22
            echo "
          <!-- End -->
        ";
            // line 24
            if ($this->getAttribute($context["product"], "price", array())) {
                // line 25
                echo "          ";
                if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                    // line 26
                    echo "              <span class=\"salepro\">In Stock</span>
             ";
                } else {
                    // line 27
                    echo "  
             <span class=\"salepro\">Out Stock</span>
          ";
                }
                // line 30
                echo "      ";
            }
            // line 31
            echo "      </div>
      <div class=\"caption text-center\">
        ";
            // line 34
            echo "        <h4><a href=\"";
            echo $this->getAttribute($context["product"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["product"], "name", array());
            echo "</a></h4>
        ";
            // line 35
            if ($this->getAttribute($context["product"], "price", array())) {
                // line 36
                echo "        <p class=\"price\">
          ";
                // line 37
                if ( !$this->getAttribute($context["product"], "special", array())) {
                    // line 38
                    echo "          ";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "
          ";
                } else {
                    // line 40
                    echo "          <span class=\"price-new\">";
                    echo $this->getAttribute($context["product"], "special", array());
                    echo "</span> <span class=\"price-old\">";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "</span>
          ";
                }
                // line 42
                echo "         ";
                // line 45
                echo "          
        </p>
             ";
                // line 47
                if (($this->getAttribute($context["product"], "discount", array()) > 0)) {
                    echo " 
          <span style=\"left:132px;\" class=\"salepro\">";
                    // line 48
                    echo $this->getAttribute($context["product"], "discount", array());
                    echo "% Off</span>          
          ";
                }
                // line 49
                echo " 

        ";
            }
            // line 52
            echo "        
        ";
            // line 53
            if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                echo "  
        <div class=\"prd_cart_{product.product_id}}\">  
          <div class=\"col-xs-12\"> 
             ";
                // line 56
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 57
                    echo "
             ";
                } else {
                    // line 59
                    echo "           <div class=\"col-sm-5 col-xs-4 pad-0 cls_qty_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\">
            <input type=\"hidden\" name=\"qty\" id=\"qty_";
                    // line 60
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"1\" value=\"";
                    echo $this->getAttribute($context["product"], "minimum", array());
                    echo "\" >
            <div class=\"input-group\" qa=\"qty\"><span class=\"input-group-addon\">Qty</span></div>
          </div>
          ";
                }
                // line 64
                echo "
         ";
                // line 65
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 66
                    echo "
          <input type=\"button\" id=\"decrease\"  class =\"decrease_";
                    // line 67
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " minus\" value=\"-\"  data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"/>
          ";
                } else {
                    // line 69
                    echo "          <input type=\"button\" id=\"decrease\" style=\"display:none;\" class =\"decrease_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " minus\" value=\"-\"  data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"/>
          ";
                }
                // line 71
                echo "
          ";
                // line 72
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 73
                    echo "          <input type=\"text\" name=\"qty\" id=\"quantity_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"5\" value=\"";
                    echo $this->getAttribute($context["product"], "cqty", array());
                    echo "\" maxlength=\"2\" >
          ";
                } else {
                    // line 75
                    echo "          <input type=\"text\" name=\"qty\" id=\"quantity_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"5\" value=\"";
                    echo $this->getAttribute($context["product"], "minimum", array());
                    echo "\" maxlength=\"2\" >
          ";
                }
                // line 77
                echo "
        ";
                // line 78
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    echo "          
          <input type=\"button\" id=\"increase\"  class=\"increase_";
                    // line 79
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " plus \" data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" value=\"+\" />
        ";
                } else {
                    // line 81
                    echo "        <input type=\"button\" id=\"increase\" style=\"display:none;\" class=\"increase_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " plus \" data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" value=\"+\" />
        ";
                }
                // line 83
                echo "          </div>
     
        </div>
        ";
            }
            // line 87
            echo "        <div class=\"clearfix\"></div><br/><br/>
         ";
            // line 88
            if ($this->getAttribute($context["product"], "rating", array())) {
                // line 89
                echo "          <div class=\"rating\">
            ";
                // line 90
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 91
                    echo "            ";
                    if (($this->getAttribute($context["product"], "rating", array()) < $context["i"])) {
                        // line 92
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    } else {
                        // line 96
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    }
                    // line 100
                    echo "          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                echo "</div>";
            } else {
                // line 101
                echo "          <div class=\"rating\">";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 102
                    echo "          <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 103
                echo "</div>
        ";
            }
            // line 105
            echo "      </div>
      <div class=\"button-group text-center m-button \">
        ";
            // line 107
            if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                // line 108
                echo "        ";
                if (($this->getAttribute($context["product"], "button", array()) != "yes")) {
                    // line 109
                    echo "          <button type=\"button\" onclick=\"cart.add('";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "');\" class=\"pcart crt_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"> <span class=\"hidden-xs\">";
                    echo (isset($context["button_cart"]) ? $context["button_cart"] : null);
                    echo "</span> </button>
        ";
                }
                // line 111
                echo "        ";
            }
            // line 112
            echo "
       
        <button type=\"button\" data-toggle=\"tooltip\" title=\"";
            // line 114
            echo (isset($context["button_wishlist"]) ? $context["button_wishlist"] : null);
            echo "\" onclick=\"wishlist.add('";
            echo $this->getAttribute($context["product"], "product_id", array());
            echo "');\" class=\"pwish\"><i class=\"fa fa-heart-o\"></i><span class=\"hidden-xs\">";
            echo "</span></button>
        <!--<button type=\"button\" data-toggle=\"tooltip\" title=\"";
            // line 115
            echo (isset($context["button_compare"]) ? $context["button_compare"] : null);
            echo "\" onclick=\"compare.add('";
            echo $this->getAttribute($context["product"], "product_id", array());
            echo "');\" class=\"pcom\">";
            echo "<span>+ ";
            echo (isset($context["button_compare"]) ? $context["button_compare"] : null);
            echo "</span></button>-->
      </div>
    </div>
  </div>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 120
        echo "</div>
</div>
</div>



<script type=\"text/javascript\">
  
    \$(document).ready(function() {
    \$(\"#bestpro\").owlCarousel({
    itemsCustom : [
    [0, 1],
    [600, 2],
    [768, 1]
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
        return "default/template/extension/module/bestproduct.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  372 => 120,  356 => 115,  349 => 114,  345 => 112,  342 => 111,  332 => 109,  329 => 108,  327 => 107,  323 => 105,  319 => 103,  312 => 102,  307 => 101,  299 => 100,  293 => 96,  287 => 92,  284 => 91,  280 => 90,  277 => 89,  275 => 88,  272 => 87,  266 => 83,  258 => 81,  251 => 79,  247 => 78,  244 => 77,  236 => 75,  228 => 73,  226 => 72,  223 => 71,  215 => 69,  208 => 67,  205 => 66,  203 => 65,  200 => 64,  191 => 60,  186 => 59,  182 => 57,  180 => 56,  174 => 53,  171 => 52,  166 => 49,  161 => 48,  157 => 47,  153 => 45,  151 => 42,  143 => 40,  137 => 38,  135 => 37,  132 => 36,  130 => 35,  123 => 34,  119 => 31,  116 => 30,  111 => 27,  107 => 26,  104 => 25,  102 => 24,  98 => 22,  92 => 21,  85 => 19,  82 => 18,  80 => 17,  73 => 16,  70 => 15,  65 => 14,  60 => 13,  58 => 12,  45 => 10,  37 => 9,  34 => 8,  30 => 7,  24 => 4,  19 => 1,);
    }
}
/* */
/* */
/* <div class="pro-bg">*/
/* <h2>{{ bestpro_title }}</h2><hr>*/
/* <div class="pro-nepr">*/
/*   <div id="bestpro" class="owl-theme owl-carousel">*/
/*  {% for product in products %}*/
/*   <div class="product-layout">*/
/*     <div class="product-thumb transition cart_div_{{ product.product_id }} {% if product.background == 'yes' %} selected-product {% endif %}">*/
/*       <div class="image alert_{{product.product_id}}"><a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-responsive center-block" /></a>*/
/*           <!-- insp Images Start -->*/
/*                  {% set t = 0 %}*/
/*                   {% for more_image in product.more_images %}*/
/*                   {% for pop in more_image %}*/
/*                   {% if t == 0 %}*/
/*                     <a href="{{ product.href }}"><img src="{{ pop.popup_more }}" class="img-responsive second-img" alt="hover image"/></a>*/
/*                   {% set t = t + 1 %}*/
/*                     {% endif %}*/
/* */
/*                   {% endfor %}*/
/*                   {% endfor %}*/
/* */
/*           <!-- End -->*/
/*         {% if product.price %}*/
/*           {% if product.quantity > 0 %}*/
/*               <span class="salepro">In Stock</span>*/
/*              {% else %}  */
/*              <span class="salepro">Out Stock</span>*/
/*           {% endif %}*/
/*       {% endif %}*/
/*       </div>*/
/*       <div class="caption text-center">*/
/*         {# <p>{{ product.description }}</p> #}*/
/*         <h4><a href="{{ product.href }}">{{ product.name }}</a></h4>*/
/*         {% if product.price %}*/
/*         <p class="price">*/
/*           {% if not product.special %}*/
/*           {{ product.price }}*/
/*           {% else %}*/
/*           <span class="price-new">{{ product.special }}</span> <span class="price-old">{{ product.price }}</span>*/
/*           {% endif %}*/
/*          {#  {% if product.tax %}*/
/*           <span class="price-tax">{{ text_tax }} {{ product.tax }}</span>*/
/*           {% endif %} #}*/
/*           */
/*         </p>*/
/*              {% if product.discount > 0 %} */
/*           <span style="left:132px;" class="salepro">{{ product.discount }}% Off</span>          */
/*           {% endif %} */
/* */
/*         {% endif %}*/
/*         */
/*         {% if product.quantity > 0 %}  */
/*         <div class="prd_cart_{product.product_id}}">  */
/*           <div class="col-xs-12"> */
/*              {% if product.cqty %}*/
/* */
/*              {% else %}*/
/*            <div class="col-sm-5 col-xs-4 pad-0 cls_qty_{{product.product_id}}">*/
/*             <input type="hidden" name="qty" id="qty_{{product.product_id}}" size="1" value="{{product.minimum}}" >*/
/*             <div class="input-group" qa="qty"><span class="input-group-addon">Qty</span></div>*/
/*           </div>*/
/*           {%endif %}*/
/* */
/*          {% if product.cqty %}*/
/* */
/*           <input type="button" id="decrease"  class ="decrease_{{product.product_id}} minus" value="-"  data-id="{{product.product_id}}"/>*/
/*           {% else %}*/
/*           <input type="button" id="decrease" style="display:none;" class ="decrease_{{product.product_id}} minus" value="-"  data-id="{{product.product_id}}"/>*/
/*           {% endif%}*/
/* */
/*           {% if product.cqty %}*/
/*           <input type="text" name="qty" id="quantity_{{product.product_id}}" size="5" value="{{product.cqty}}" maxlength="2" >*/
/*           {% else %}*/
/*           <input type="text" name="qty" id="quantity_{{product.product_id}}" size="5" value="{{product.minimum}}" maxlength="2" >*/
/*           {% endif %}*/
/* */
/*         {% if product.cqty %}          */
/*           <input type="button" id="increase"  class="increase_{{product.product_id}} plus " data-id="{{product.product_id}}" value="+" />*/
/*         {% else %}*/
/*         <input type="button" id="increase" style="display:none;" class="increase_{{product.product_id}} plus " data-id="{{product.product_id}}" value="+" />*/
/*         {% endif %}*/
/*           </div>*/
/*      */
/*         </div>*/
/*         {% endif %}*/
/*         <div class="clearfix"></div><br/><br/>*/
/*          {% if product.rating %}*/
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
/*       </div>*/
/*       <div class="button-group text-center m-button ">*/
/*         {% if product.quantity > 0 %}*/
/*         {% if product.button!='yes' %}*/
/*           <button type="button" onclick="cart.add('{{ product.product_id }}');" class="pcart crt_{{product.product_id}}"> <span class="hidden-xs">{{ button_cart }}</span> </button>*/
/*         {% endif %}*/
/*         {% endif %}*/
/* */
/*        */
/*         <button type="button" data-toggle="tooltip" title="{{ button_wishlist }}" onclick="wishlist.add('{{ product.product_id }}');" class="pwish"><i class="fa fa-heart-o"></i><span class="hidden-xs">{# {{ button_wishlist }} #}</span></button>*/
/*         <!--<button type="button" data-toggle="tooltip" title="{{ button_compare }}" onclick="compare.add('{{ product.product_id }}');" class="pcom">{# <i class="fa fa-compress"></i><span class="hidden-xs"> #}<span>+ {{ button_compare }}</span></button>-->*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/*   {% endfor %}*/
/* </div>*/
/* </div>*/
/* </div>*/
/* */
/* */
/* */
/* <script type="text/javascript">*/
/*   */
/*     $(document).ready(function() {*/
/*     $("#bestpro").owlCarousel({*/
/*     itemsCustom : [*/
/*     [0, 1],*/
/*     [600, 2],*/
/*     [768, 1]*/
/*     ],*/
/*       // autoPlay: 1000,*/
/*       navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],*/
/*       navigation : true,*/
/*       pagination:false*/
/*     });*/
/*     });*/
/* </script>*/
