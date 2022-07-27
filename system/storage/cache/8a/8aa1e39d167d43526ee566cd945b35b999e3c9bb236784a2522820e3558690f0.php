<?php

/* __string_template__d0175aa7ebb950380d2b8c883fc450af1f80a6d93f5ae41a93788a4d168be432 */
class __TwigTemplate_ac9ea7d5fdfd669659cebda1542755a78ef406f4502ef2ce03f2daaa6c4b5ba2 extends Twig_Template
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
        echo "<footer>

<div>
      ";
        // line 4
        echo (isset($context["ftop_full"]) ? $context["ftop_full"] : null);
        echo "
</div>
  <div class=\"container\">
    <div class=\"row\">

    <div class=\"middle-footer\">

   ";
        // line 18
        echo "      <div class=\"col-sm-3\">
        <div class=\"xs-fab\">
          <h5 class=\"\">";
        // line 20
        echo (isset($context["text_extra"]) ? $context["text_extra"] : null);
        echo "<button type=\"button\" class=\"btn btn-primary toggle collapsed\" data-toggle=\"collapse\" data-target=\"#aboutf\"></button></h5>
          <div id=\"aboutf\" class=\"collapse footer-collapse\">            
            <!-- ";
        // line 22
        echo (isset($context["footer_left"]) ? $context["footer_left"] : null);
        echo " -->
            <ul class=\"list-unstyled\">         
          <li><a href=\"";
        // line 24
        echo (isset($context["contact"]) ? $context["contact"] : null);
        echo "\">";
        echo (isset($context["text_contact"]) ? $context["text_contact"] : null);
        echo "</a></li>
          <li><a href=\"";
        // line 25
        echo (isset($context["special"]) ? $context["special"] : null);
        echo "\">";
        echo (isset($context["text_special"]) ? $context["text_special"] : null);
        echo "</a></li>
          <li><a href=\"";
        // line 26
        echo (isset($context["sitemap"]) ? $context["sitemap"] : null);
        echo "\">";
        echo (isset($context["text_sitemap"]) ? $context["text_sitemap"] : null);
        echo "</a></li>
          </ul>
          </div>
        </div>
      </div>
      <div class=\"col-sm-3\">
        ";
        // line 32
        if ((isset($context["informations"]) ? $context["informations"] : null)) {
            // line 33
            echo "        <h5>";
            echo (isset($context["text_information"]) ? $context["text_information"] : null);
            echo "
          <button type=\"button\" class=\"btn btn-primary toggle collapsed\" data-toggle=\"collapse\" data-target=\"#info\"></button>
        </h5>
        <div id=\"info\" class=\"collapse footer-collapse\">
        <ul class=\"list-unstyled\">
         ";
            // line 38
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["informations"]) ? $context["informations"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["information"]) {
                // line 39
                echo "          <li><a href=\"";
                echo $this->getAttribute($context["information"], "href", array());
                echo "\">";
                echo $this->getAttribute($context["information"], "title", array());
                echo "</a></li>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['information'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 40
            echo "            
        </ul>
        </div>
        ";
        }
        // line 44
        echo "      </div>
      <div class=\"col-sm-3\">
        <h5>";
        // line 46
        echo (isset($context["text_account"]) ? $context["text_account"] : null);
        echo "
          <button type=\"button\" class=\"btn btn-primary toggle collapsed\" data-toggle=\"collapse\" data-target=\"#account\"></button>
        </h5>
        <div id=\"account\" class=\"collapse footer-collapse\">
        <ul class=\"list-unstyled lastb\">
          <li><a href=\"";
        // line 51
        echo (isset($context["account"]) ? $context["account"] : null);
        echo "\">";
        echo (isset($context["text_account"]) ? $context["text_account"] : null);
        echo "</a></li>
          <li><a href=\"";
        // line 52
        echo (isset($context["order"]) ? $context["order"] : null);
        echo "\">";
        echo (isset($context["text_order"]) ? $context["text_order"] : null);
        echo "</a></li>
          <li><a href=\"";
        // line 53
        echo (isset($context["return"]) ? $context["return"] : null);
        echo "\">";
        echo (isset($context["text_return"]) ? $context["text_return"] : null);
        echo "</a></li>            
        </ul>
        </div>
      </div>
      <div class=\"col-sm-3\">
        <div class=\"xs-fab\">
          ";
        // line 59
        echo (isset($context["footer_right"]) ? $context["footer_right"] : null);
        echo "
        </div>
      </div>
    </div>
     <!--  ";
        // line 63
        echo (isset($context["footer_right"]) ? $context["footer_right"] : null);
        echo " -->
    </div>
  </div>
  <div class=\"container\">
    <div class=\"row\">
      <div class=\"col-md-3 f-social\">
        <ul class=\"list-inline list-unstyled foot-social\">
        <li><a href=\"https://www.facebook.com/iavocadogurgaon/\"><i class=\"fa fa-facebook\"></i></a></li>
        <li><a href=\"https://www.instagram.com/iavocado_india/\"><i class=\"fa fa-instagram\"></i></a></li>
        <li><a href=\"#\"><i class=\"fa fa-pinterest-p\"></i></a></li>
        <li><a href=\"#\"><i class=\"fa fa-google-plus\"></i></a></li>
        </ul>
      </div>
    </div>
      <div class=\"foot-bottom\"><div>  <div class=\"row\">
  <div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 f-social\">
      <div class=\"foot-app\">
      <div class=\"title-footer hidden-md hidden-sm hidden-xs\"><a href=\"https://play.google.com/store/apps/details?id=iavo.iavocado\">Download Our App</a></div>
      <ul class=\"list-unstyled inline-block\">       
        <li><a title=\"PlayStore\" href=\"https://play.google.com/store/apps/details?id=iavo.iavocado\"><img class=\"img-responsive\" src=\"image/catalog/app2.png\" alt=\"Play Store\"></a></li>
      </ul>
    </div>        
  </div>
  <div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right\">
    <div class=\"text-right\">
       <img class=\"img-responsive\" src=\"image/catalog/payment.png\" alt=\"imgpayment\">
    </div>
  </div>
</div></div>
</div>
  </div>
  <div class=\"foot-power\">
  <div class=\"container\">
      <div class=\"copy\">
       <!-- <div class=\"pull-right hidden-xs\">
          <ul class=\"list-inline list-unstyled foot-payment\">
          <li><a href=\"#\"><i class=\"fa fa-cc-mastercard\"></i></a></li>
          <li><a href=\"#\"><i class=\"fa fa-cc-visa\"></i></a></li>
          <li><a href=\"#\"><i class=\"fa fa-credit-card\"></i></a></li>
          <li><a href=\"#\"><i class=\"fa fa-cc-paypal\"></i></a></li>
          </ul>
      </div> -->
      </div>
      
    </div>
  </div>
</footer>
<a href=\"\" id=\"scroll\" title=\"Scroll to Top\" style=\"display: block;\">
    <i class=\"fa fa-caret-up\"></i>
</a>
      ";
        // line 113
        echo (isset($context["fbottom_full"]) ? $context["fbottom_full"] : null);
        echo "


";
        // line 116
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["scripts"]) ? $context["scripts"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
            // line 117
            echo "<script defer='defer' src=\"";
            echo $context["script"];
            echo "\" type=\"text/javascript\"></script>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['script'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 119
        echo "<script  type=\"text/javascript\">
\$(document).ready(function(){
  \$(\".col-md-3 #cat-click\").click(function(){    
    \$(\".navbar\").show();
  });  
  if(navigator.userAgent.match(/Android/i)
 || navigator.userAgent.match(/webOS/i)
 || navigator.userAgent.match(/iPhone/i)
 || navigator.userAgent.match(/iPad/i)
 || navigator.userAgent.match(/iPod/i)
 || navigator.userAgent.match(/BlackBerry/i)
 || navigator.userAgent.match(/Windows Phone/i)) {
    \$(\".navbar\").show();
  }else{
    \$(\".navbar\").hide();
  }
  
});
</script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<!--
<div id=\"my-awesome-popup-1\" class=\"modal\" tabindex=\"-1\" role=\"dialog\">
      <div class=\"modal-dialog\">
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <!--<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
            <h4 class=\"modal-title\">We are Delivering in the following Cities</h4>
          </div>
          <div class=\"modal-body\">
             <div class=\"form-group\">
          <label for=\"sel1\">Please Choose your city</label>
                  <select class=\"form-control\" id=\"select_city\" name=\"select_city\">
                  <option value=\"\">Choose your city</option>
                    <option value=\"Gurgaon\">Gurgaon</option>
                    <option value=\"Delhi\">Delhi</option>
                    <option value=\"Noida\">Noida</option>
                  </select> 
            </br></br>
                 <div class=\"form-group\">
                    <button type=\"button\" class=\"btn btn-danger cancel pull-left\">Cancel</button>
                    <button type=\"button\" class=\"btn btn-primary continue pull-right\">Continue</button>   
                 </div>
        </br></br>
               </div>
             </div>
          </div>
        </div>
      </div>
    </div>
-->
<script>
  var cok=getCookie('selectcity');
 
 if(cok!='city'){ 
    \$('#my-awesome-popup-1').modal('show');
  }
  
  setCookie('selectcity','city',2);
  
  \$('.continue').on('click',function(){
    var val=\$('#select_city').val();
  
   if(val){
      setCookie('selectcity','city',2);
      \$('#my-awesome-popup-1').modal('hide');
    }else{
      \$('#select_city').focus();
      \$('#select_city').css('border','red');
    } 
  })
  
  
  \$('.cancel').on('click',function(){
      \$('#my-awesome-popup-1').modal('hide');
  })
  function setCookie(name, value, days) {
  var expires = \"\";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = \"; expires=\" + date.toUTCString();
  }
  document.cookie = name + \"=\" + (value || \"\") + expires + \"; path=/\";
}

function getCookie(name) {
  var nameEQ = name + \"=\";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}
</script>
<!--Start of Tawk.to Script-->
<script defer='defer' type=\"text/javascript\">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5ec10198967ae56c521a8b35/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body></html>
";
    }

    public function getTemplateName()
    {
        return "__string_template__d0175aa7ebb950380d2b8c883fc450af1f80a6d93f5ae41a93788a4d168be432";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  217 => 119,  208 => 117,  204 => 116,  198 => 113,  145 => 63,  138 => 59,  127 => 53,  121 => 52,  115 => 51,  107 => 46,  103 => 44,  97 => 40,  86 => 39,  82 => 38,  73 => 33,  71 => 32,  60 => 26,  54 => 25,  48 => 24,  43 => 22,  38 => 20,  34 => 18,  24 => 4,  19 => 1,);
    }
}
/* <footer>*/
/* */
/* <div>*/
/*       {{ ftop_full }}*/
/* </div>*/
/*   <div class="container">*/
/*     <div class="row">*/
/* */
/*     <div class="middle-footer">*/
/* */
/*    {#  {% if footer_left and footer_right %}*/
/*     {% set class = 'col-sm-8' %}*/
/*     {% elseif footer_left or footer_right %}*/
/*     {% set class = 'col-sm-9' %}*/
/*     {% else %}*/
/*     {% set class = 'col-sm-12' %}*/
/*     {% endif %} #}*/
/*       <div class="col-sm-3">*/
/*         <div class="xs-fab">*/
/*           <h5 class="">{{ text_extra }}<button type="button" class="btn btn-primary toggle collapsed" data-toggle="collapse" data-target="#aboutf"></button></h5>*/
/*           <div id="aboutf" class="collapse footer-collapse">            */
/*             <!-- {{ footer_left }} -->*/
/*             <ul class="list-unstyled">         */
/*           <li><a href="{{ contact }}">{{ text_contact }}</a></li>*/
/*           <li><a href="{{ special }}">{{ text_special }}</a></li>*/
/*           <li><a href="{{ sitemap }}">{{ text_sitemap }}</a></li>*/
/*           </ul>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col-sm-3">*/
/*         {% if informations %}*/
/*         <h5>{{ text_information }}*/
/*           <button type="button" class="btn btn-primary toggle collapsed" data-toggle="collapse" data-target="#info"></button>*/
/*         </h5>*/
/*         <div id="info" class="collapse footer-collapse">*/
/*         <ul class="list-unstyled">*/
/*          {% for information in informations %}*/
/*           <li><a href="{{ information.href }}">{{ information.title }}</a></li>*/
/*           {% endfor %}            */
/*         </ul>*/
/*         </div>*/
/*         {% endif %}*/
/*       </div>*/
/*       <div class="col-sm-3">*/
/*         <h5>{{ text_account }}*/
/*           <button type="button" class="btn btn-primary toggle collapsed" data-toggle="collapse" data-target="#account"></button>*/
/*         </h5>*/
/*         <div id="account" class="collapse footer-collapse">*/
/*         <ul class="list-unstyled lastb">*/
/*           <li><a href="{{ account }}">{{ text_account }}</a></li>*/
/*           <li><a href="{{ order }}">{{ text_order }}</a></li>*/
/*           <li><a href="{{ return }}">{{ text_return }}</a></li>            */
/*         </ul>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col-sm-3">*/
/*         <div class="xs-fab">*/
/*           {{ footer_right }}*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*      <!--  {{ footer_right }} -->*/
/*     </div>*/
/*   </div>*/
/*   <div class="container">*/
/*     <div class="row">*/
/*       <div class="col-md-3 f-social">*/
/*         <ul class="list-inline list-unstyled foot-social">*/
/*         <li><a href="https://www.facebook.com/iavocadogurgaon/"><i class="fa fa-facebook"></i></a></li>*/
/*         <li><a href="https://www.instagram.com/iavocado_india/"><i class="fa fa-instagram"></i></a></li>*/
/*         <li><a href="#"><i class="fa fa-pinterest-p"></i></a></li>*/
/*         <li><a href="#"><i class="fa fa-google-plus"></i></a></li>*/
/*         </ul>*/
/*       </div>*/
/*     </div>*/
/*       <div class="foot-bottom"><div>  <div class="row">*/
/*   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 f-social">*/
/*       <div class="foot-app">*/
/*       <div class="title-footer hidden-md hidden-sm hidden-xs"><a href="https://play.google.com/store/apps/details?id=iavo.iavocado">Download Our App</a></div>*/
/*       <ul class="list-unstyled inline-block">       */
/*         <li><a title="PlayStore" href="https://play.google.com/store/apps/details?id=iavo.iavocado"><img class="img-responsive" src="image/catalog/app2.png" alt="Play Store"></a></li>*/
/*       </ul>*/
/*     </div>        */
/*   </div>*/
/*   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">*/
/*     <div class="text-right">*/
/*        <img class="img-responsive" src="image/catalog/payment.png" alt="imgpayment">*/
/*     </div>*/
/*   </div>*/
/* </div></div>*/
/* </div>*/
/*   </div>*/
/*   <div class="foot-power">*/
/*   <div class="container">*/
/*       <div class="copy">*/
/*        <!-- <div class="pull-right hidden-xs">*/
/*           <ul class="list-inline list-unstyled foot-payment">*/
/*           <li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>*/
/*           <li><a href="#"><i class="fa fa-cc-visa"></i></a></li>*/
/*           <li><a href="#"><i class="fa fa-credit-card"></i></a></li>*/
/*           <li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>*/
/*           </ul>*/
/*       </div> -->*/
/*       </div>*/
/*       */
/*     </div>*/
/*   </div>*/
/* </footer>*/
/* <a href="" id="scroll" title="Scroll to Top" style="display: block;">*/
/*     <i class="fa fa-caret-up"></i>*/
/* </a>*/
/*       {{ fbottom_full }}*/
/* */
/* */
/* {% for script in scripts %}*/
/* <script defer='defer' src="{{ script }}" type="text/javascript"></script>*/
/* {% endfor %}*/
/* <script  type="text/javascript">*/
/* $(document).ready(function(){*/
/*   $(".col-md-3 #cat-click").click(function(){    */
/*     $(".navbar").show();*/
/*   });  */
/*   if(navigator.userAgent.match(/Android/i)*/
/*  || navigator.userAgent.match(/webOS/i)*/
/*  || navigator.userAgent.match(/iPhone/i)*/
/*  || navigator.userAgent.match(/iPad/i)*/
/*  || navigator.userAgent.match(/iPod/i)*/
/*  || navigator.userAgent.match(/BlackBerry/i)*/
/*  || navigator.userAgent.match(/Windows Phone/i)) {*/
/*     $(".navbar").show();*/
/*   }else{*/
/*     $(".navbar").hide();*/
/*   }*/
/*   */
/* });*/
/* </script>*/
/* <!--*/
/* OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.*/
/* Please donate via PayPal to donate@opencart.com*/
/* //-->*/
/* <!--*/
/* <div id="my-awesome-popup-1" class="modal" tabindex="-1" role="dialog">*/
/*       <div class="modal-dialog">*/
/*         <div class="modal-content">*/
/*           <div class="modal-header">*/
/*             <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>*/
/*             <h4 class="modal-title">We are Delivering in the following Cities</h4>*/
/*           </div>*/
/*           <div class="modal-body">*/
/*              <div class="form-group">*/
/*           <label for="sel1">Please Choose your city</label>*/
/*                   <select class="form-control" id="select_city" name="select_city">*/
/*                   <option value="">Choose your city</option>*/
/*                     <option value="Gurgaon">Gurgaon</option>*/
/*                     <option value="Delhi">Delhi</option>*/
/*                     <option value="Noida">Noida</option>*/
/*                   </select> */
/*             </br></br>*/
/*                  <div class="form-group">*/
/*                     <button type="button" class="btn btn-danger cancel pull-left">Cancel</button>*/
/*                     <button type="button" class="btn btn-primary continue pull-right">Continue</button>   */
/*                  </div>*/
/*         </br></br>*/
/*                </div>*/
/*              </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/* -->*/
/* <script>*/
/*   var cok=getCookie('selectcity');*/
/*  */
/*  if(cok!='city'){ */
/*     $('#my-awesome-popup-1').modal('show');*/
/*   }*/
/*   */
/*   setCookie('selectcity','city',2);*/
/*   */
/*   $('.continue').on('click',function(){*/
/*     var val=$('#select_city').val();*/
/*   */
/*    if(val){*/
/*       setCookie('selectcity','city',2);*/
/*       $('#my-awesome-popup-1').modal('hide');*/
/*     }else{*/
/*       $('#select_city').focus();*/
/*       $('#select_city').css('border','red');*/
/*     } */
/*   })*/
/*   */
/*   */
/*   $('.cancel').on('click',function(){*/
/*       $('#my-awesome-popup-1').modal('hide');*/
/*   })*/
/*   function setCookie(name, value, days) {*/
/*   var expires = "";*/
/*   if (days) {*/
/*     var date = new Date();*/
/*     date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));*/
/*     expires = "; expires=" + date.toUTCString();*/
/*   }*/
/*   document.cookie = name + "=" + (value || "") + expires + "; path=/";*/
/* }*/
/* */
/* function getCookie(name) {*/
/*   var nameEQ = name + "=";*/
/*   var ca = document.cookie.split(';');*/
/*   for (var i = 0; i < ca.length; i++) {*/
/*     var c = ca[i];*/
/*     while (c.charAt(0) == ' ') c = c.substring(1, c.length);*/
/*     if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);*/
/*   }*/
/*   return null;*/
/* }*/
/* </script>*/
/* <!--Start of Tawk.to Script-->*/
/* <script defer='defer' type="text/javascript">*/
/* var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();*/
/* (function(){*/
/* var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];*/
/* s1.async=true;*/
/* s1.src='https://embed.tawk.to/5ec10198967ae56c521a8b35/default';*/
/* s1.charset='UTF-8';*/
/* s1.setAttribute('crossorigin','*');*/
/* s0.parentNode.insertBefore(s1,s0);*/
/* })();*/
/* </script>*/
/* <!--End of Tawk.to Script-->*/
/* </body></html>*/
/* */
