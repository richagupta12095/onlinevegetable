<?php

/* default/template/extension/module/bestseller.twig */
class __TwigTemplate_cd43bba967943de123e40e88de4ec3270e225cb04c6c23f5c069a373f568f4b3 extends Twig_Template
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
  <h2>";
        // line 2
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h2><hr>
<div class=\"pro-nepr\" id=\"featurep\">


  <div id=\"bestseller\" class=\"owl-theme owl-carousel\">
 ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["products"]) ? $context["products"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 8
            echo " 
  <div class=\"product-layout col-xs-12 \">
    <div class=\"product-thumb transition   cart_div_";
            // line 10
            echo $this->getAttribute($context["product"], "product_id", array());
            echo " ";
            if (($this->getAttribute($context["product"], "background", array()) == "yes")) {
                echo " selected-product ";
            }
            echo "\" \">
      <div class=\"image alert_";
            // line 11
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
            // line 13
            $context["t"] = 0;
            // line 14
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["product"], "insp_images", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["insp_image"]) {
                // line 15
                echo "                          ";
                if (((isset($context["t"]) ? $context["t"] : null) == 0)) {
                    // line 16
                    echo "                           <a href=\"";
                    echo $this->getAttribute($context["product"], "href", array());
                    echo "\"><img src=\"";
                    echo $this->getAttribute($context["insp_image"], "popup", array());
                    echo "\" class=\"img-responsive second-img\" alt=\"hover image\"/></a>
                          ";
                    // line 17
                    $context["t"] = ((isset($context["t"]) ? $context["t"] : null) + 1);
                    // line 18
                    echo "                          ";
                }
                // line 19
                echo "                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['insp_image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 20
            echo "        <!-- insp Images End -->
        ";
            // line 21
            if ($this->getAttribute($context["product"], "price", array())) {
                // line 22
                echo "          ";
                if ($this->getAttribute($context["product"], "special", array())) {
                    // line 23
                    echo "              ";
                    if (($this->getAttribute($context["product"], "quantity", array()) == 0)) {
                        echo " 
                <span class=\"salepro\">Out of Stock</span>
              ";
                    } else {
                        // line 26
                        echo "                <span class=\"salepro\">In Stock</span>
              ";
                    }
                    // line 28
                    echo "          ";
                }
                // line 29
                echo "            ";
                if (($this->getAttribute($context["product"], "discount", array()) > 0)) {
                    echo " 
          <span style=\"left:132px;\" class=\"salepro\">";
                    // line 30
                    echo $this->getAttribute($context["product"], "discount", array());
                    echo "% Off</span>          
          ";
                }
                // line 31
                echo " 
          
      ";
            }
            // line 34
            echo "      </div>
      <div class=\"caption text-center \">
        ";
            // line 37
            echo "        <h4><a href=\"";
            echo $this->getAttribute($context["product"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["product"], "name", array());
            echo "</a></h4>
        ";
            // line 38
            if ($this->getAttribute($context["product"], "price", array())) {
                // line 39
                echo "        <p class=\"price\">
          ";
                // line 40
                if ( !$this->getAttribute($context["product"], "special", array())) {
                    // line 41
                    echo "          ";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "
          ";
                } else {
                    // line 43
                    echo "          <span class=\"price-new\">";
                    echo $this->getAttribute($context["product"], "special", array());
                    echo "</span> <span class=\"price-old\">";
                    echo $this->getAttribute($context["product"], "price", array());
                    echo "</span>
          ";
                }
                // line 45
                echo "         ";
                // line 48
                echo "        </p>
        ";
            }
            // line 50
            echo "
          ";
            // line 51
            if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                echo "  
        <div class=\"prd_cart_{product.product_id}}\">  
          <div class=\"col-xs-12\"> 
             ";
                // line 54
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 55
                    echo "
             ";
                } else {
                    // line 57
                    echo "           <div class=\"col-sm-5 col-xs-4 pad-0 cls_qty_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\">
            <input type=\"hidden\" name=\"qty\" id=\"qty_";
                    // line 58
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"1\" value=\"";
                    echo $this->getAttribute($context["product"], "minimum", array());
                    echo "\" >
            <div class=\"input-group\" qa=\"qty\"><span class=\"input-group-addon\">Qty</span></div>
          </div>
          ";
                }
                // line 62
                echo "         ";
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 63
                    echo "
          <input type=\"button\" id=\"decrease\"  class =\"decrease_";
                    // line 64
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " minus\" value=\"-\"  data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"/>
          ";
                } else {
                    // line 66
                    echo "          <input type=\"button\" id=\"decrease\" style=\"display:none;\" class =\"decrease_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " minus\" value=\"-\"  data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"/>
          ";
                }
                // line 68
                echo "
          ";
                // line 69
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    // line 70
                    echo "          <input type=\"text\" name=\"qty\" id=\"quantity_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"3\" value=\"";
                    echo $this->getAttribute($context["product"], "cqty", array());
                    echo "\" maxlength=\"2\" >
          ";
                } else {
                    // line 72
                    echo "          <input type=\"text\" name=\"qty\" id=\"quantity_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" size=\"3\" value=\"";
                    echo $this->getAttribute($context["product"], "minimum", array());
                    echo "\" maxlength=\"2\" >
          ";
                }
                // line 74
                echo "
        ";
                // line 75
                if ($this->getAttribute($context["product"], "cqty", array())) {
                    echo "          
          <input type=\"button\" id=\"increase\"  class=\"increase_";
                    // line 76
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " plus \" data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" value=\"+\" />
        ";
                } else {
                    // line 78
                    echo "        <input type=\"button\" id=\"increase\" style=\"display:none;\" class=\"increase_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo " plus \" data-id=\"";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\" value=\"+\" />
        ";
                }
                // line 80
                echo "          </div>
     
        </div>
        ";
            }
            // line 84
            echo "
        <div class=\"clearfix\"></div><br/><br/>

         ";
            // line 87
            if ($this->getAttribute($context["product"], "rating", array())) {
                // line 88
                echo "          <div class=\"rating\">
            ";
                // line 89
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 90
                    echo "            ";
                    if (($this->getAttribute($context["product"], "rating", array()) < $context["i"])) {
                        // line 91
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    } else {
                        // line 95
                        echo "            <span class=\"fa fa-stack\">
              <i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i>
            </span>
            ";
                    }
                    // line 99
                    echo "          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                echo "</div>";
            } else {
                // line 100
                echo "          <div class=\"rating\">";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 101
                    echo "          <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 102
                echo "</div>
        ";
            }
            // line 104
            echo "      </div>
      <div class=\"button-group text-center m-button\">
          ";
            // line 106
            if (($this->getAttribute($context["product"], "quantity", array()) > 0)) {
                echo "      
           ";
                // line 107
                if (($this->getAttribute($context["product"], "button", array()) != "yes")) {
                    echo "  
        <button type=\"button\" onclick=\"cart.add('";
                    // line 108
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "');\" class=\"pcart pcart crt_";
                    echo $this->getAttribute($context["product"], "product_id", array());
                    echo "\"\">
          <span>";
                    // line 109
                    echo (isset($context["button_cart"]) ? $context["button_cart"] : null);
                    echo "</span>
        </button>
        ";
                }
                // line 112
                echo "        ";
            }
            // line 113
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
    \$(\"#bestseller\").owlCarousel({
    itemsCustom : [
    [0, 1],
    [375, 2],
    [600, 3],
    [768, 2],
    [992, 3],
    [1200, 4]
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
        return "default/template/extension/module/bestseller.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  367 => 120,  351 => 115,  344 => 114,  341 => 113,  338 => 112,  332 => 109,  326 => 108,  322 => 107,  318 => 106,  314 => 104,  310 => 102,  303 => 101,  298 => 100,  290 => 99,  284 => 95,  278 => 91,  275 => 90,  271 => 89,  268 => 88,  266 => 87,  261 => 84,  255 => 80,  247 => 78,  240 => 76,  236 => 75,  233 => 74,  225 => 72,  217 => 70,  215 => 69,  212 => 68,  204 => 66,  197 => 64,  194 => 63,  191 => 62,  182 => 58,  177 => 57,  173 => 55,  171 => 54,  165 => 51,  162 => 50,  158 => 48,  156 => 45,  148 => 43,  142 => 41,  140 => 40,  137 => 39,  135 => 38,  128 => 37,  124 => 34,  119 => 31,  114 => 30,  109 => 29,  106 => 28,  102 => 26,  95 => 23,  92 => 22,  90 => 21,  87 => 20,  81 => 19,  78 => 18,  76 => 17,  69 => 16,  66 => 15,  61 => 14,  59 => 13,  46 => 11,  38 => 10,  34 => 8,  30 => 7,  22 => 2,  19 => 1,);
    }
}
/* <div class="pro-bg">*/
/*   <h2>{{ heading_title }}</h2><hr>*/
/* <div class="pro-nepr" id="featurep">*/
/* */
/* */
/*   <div id="bestseller" class="owl-theme owl-carousel">*/
/*  {% for product in products %}*/
/*  */
/*   <div class="product-layout col-xs-12 ">*/
/*     <div class="product-thumb transition   cart_div_{{ product.product_id }} {% if product.background == 'yes' %} selected-product {% endif %}" ">*/
/*       <div class="image alert_{{ product.product_id }}"><a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-responsive center-block" /></a>*/
/*           <!-- insp Images Start -->*/
/*                         {% set t = 0 %}*/
/*                           {% for insp_image in product.insp_images %}*/
/*                           {% if t == 0 %}*/
/*                            <a href="{{ product.href }}"><img src="{{ insp_image.popup }}" class="img-responsive second-img" alt="hover image"/></a>*/
/*                           {% set t = t + 1 %}*/
/*                           {% endif %}*/
/*                         {% endfor %}*/
/*         <!-- insp Images End -->*/
/*         {% if product.price %}*/
/*           {% if product.special %}*/
/*               {% if product.quantity == 0 %} */
/*                 <span class="salepro">Out of Stock</span>*/
/*               {% else %}*/
/*                 <span class="salepro">In Stock</span>*/
/*               {% endif %}*/
/*           {% endif %}*/
/*             {% if product.discount > 0 %} */
/*           <span style="left:132px;" class="salepro">{{ product.discount }}% Off</span>          */
/*           {% endif %} */
/*           */
/*       {% endif %}*/
/*       </div>*/
/*       <div class="caption text-center ">*/
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
/*         </p>*/
/*         {% endif %}*/
/* */
/*           {% if product.quantity > 0 %}  */
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
/*          {% if product.cqty %}*/
/* */
/*           <input type="button" id="decrease"  class ="decrease_{{product.product_id}} minus" value="-"  data-id="{{product.product_id}}"/>*/
/*           {% else %}*/
/*           <input type="button" id="decrease" style="display:none;" class ="decrease_{{product.product_id}} minus" value="-"  data-id="{{product.product_id}}"/>*/
/*           {% endif%}*/
/* */
/*           {% if product.cqty %}*/
/*           <input type="text" name="qty" id="quantity_{{product.product_id}}" size="3" value="{{product.cqty}}" maxlength="2" >*/
/*           {% else %}*/
/*           <input type="text" name="qty" id="quantity_{{product.product_id}}" size="3" value="{{product.minimum}}" maxlength="2" >*/
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
/* */
/*         <div class="clearfix"></div><br/><br/>*/
/* */
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
/*       <div class="button-group text-center m-button">*/
/*           {% if product.quantity > 0 %}      */
/*            {% if product.button!='yes' %}  */
/*         <button type="button" onclick="cart.add('{{ product.product_id }}');" class="pcart pcart crt_{{product.product_id}}"">*/
/*           <span>{{ button_cart }}</span>*/
/*         </button>*/
/*         {% endif %}*/
/*         {% endif %}*/
/* */
/*         <button type="button" data-toggle="tooltip" title="{{ button_wishlist }}" onclick="wishlist.add('{{ product.product_id }}');" class="pwish"><i class="fa fa-heart-o"></i><span class="hidden-xs">{# {{ button_wishlist }} #}</span></button>*/
/*         <!--<button type="button" data-toggle="tooltip" title="{{ button_compare }}" onclick="compare.add('{{ product.product_id }}');" class="pcom">{# <i class="fa fa-compress"></i><span class="hidden-xs"> #}<span>+ {{ button_compare }}</span></button>-->*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/*   {% endfor %}*/
/* </div>*/
/* </div>*/
/* </div>*/
/* <script type="text/javascript">*/
/* */
/*     $(document).ready(function() {*/
/*     $("#bestseller").owlCarousel({*/
/*     itemsCustom : [*/
/*     [0, 1],*/
/*     [375, 2],*/
/*     [600, 3],*/
/*     [768, 2],*/
/*     [992, 3],*/
/*     [1200, 4]*/
/*     ],*/
/*       // autoPlay: 1000,*/
/*       navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],*/
/*       navigation : true,*/
/*       pagination:false*/
/*     });*/
/*     });*/
/* </script>*/
