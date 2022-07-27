<?php

/* extension/dashboard/e_wallet.twig */
class __TwigTemplate_aa3ed05af87aca4745da17df47c3eaeefe7e1df596e728ae7e008e85e2eb8484 extends Twig_Template
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
        echo " <div class=\"row \">
  <div class=\"col-lg-3  col-md-3 col-sm-6\">
    <div class=\"tile tile-primary\">
      <div class=\"tile-heading\">";
        // line 4
        echo (isset($context["heading_title1"]) ? $context["heading_title1"] : null);
        echo " </div>
      <div class=\"tile-body\"><i class=\"fa fa-exchange\"></i>
        <h2 class=\"pull-right\">";
        // line 6
        echo (isset($context["totaltrasaction"]) ? $context["totaltrasaction"] : null);
        echo "</h2>        
      </div>
      <div class=\"tile-footer\"><a href=\"";
        // line 8
        echo (isset($context["e_wallet"]) ? $context["e_wallet"] : null);
        echo "\">";
        echo (isset($context["text_view"]) ? $context["text_view"] : null);
        echo "</a></div>
    </div>      
  </div>
  <div class=\"col-lg-3  col-md-3 col-sm-6\">
    <div class=\"tile tile-primary\">
      <div class=\"tile-heading\">";
        // line 13
        echo (isset($context["heading_title3"]) ? $context["heading_title3"] : null);
        echo " </div>
      <div class=\"tile-body\"><i class=\"fa fa-plus-square-o \"></i>
        
      </div>
      <div class=\"tile-footer\"><a href=\"";
        // line 17
        echo (isset($context["add"]) ? $context["add"] : null);
        echo "\">";
        echo (isset($context["text_view"]) ? $context["text_view"] : null);
        echo "</a></div>
    </div> 
  </div>
  <div class=\"col-lg-3  col-md-3 col-sm-6\">
    <div class=\"tile tile-primary\">
      <div class=\"tile-heading\">";
        // line 22
        echo (isset($context["heading_title4"]) ? $context["heading_title4"] : null);
        echo " </div>
      <div class=\"tile-body\"><i class=\"fa fa-credit-card\"></i>
      <div class=\"pull-right\" style=\"line-height: 25px;\">        
        <div >";
        // line 25
        echo (isset($context["pendding"]) ? $context["pendding"] : null);
        echo "</div>
        <div >";
        // line 26
        echo (isset($context["approve"]) ? $context["approve"] : null);
        echo "</div>
        <div >";
        // line 27
        echo (isset($context["reject"]) ? $context["reject"] : null);
        echo "</div>
      </div>
      </div>
      <div class=\"tile-footer\"><a href=\"";
        // line 30
        echo (isset($context["money"]) ? $context["money"] : null);
        echo "\">";
        echo (isset($context["text_view"]) ? $context["text_view"] : null);
        echo "</a></div>
    </div> 
  </div>
  <div class=\"col-lg-3  col-md-3 col-sm-6\">
    <div class=\"tile tile-primary\">
      <div class=\"tile-heading\">";
        // line 35
        echo (isset($context["heading_title2"]) ? $context["heading_title2"] : null);
        echo " </div>
      <div class=\"tile-body\"><i class=\"fa fa-money   \"></i>
        <h2 class=\"pull-right\">";
        // line 37
        echo (isset($context["balance"]) ? $context["balance"] : null);
        echo "</h2>
      </div>
      <div class=\"tile-footer\"><a href=\"";
        // line 39
        echo (isset($context["customer"]) ? $context["customer"] : null);
        echo "\">";
        echo (isset($context["text_view"]) ? $context["text_view"] : null);
        echo "</a></div>
    </div> 
  </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "extension/dashboard/e_wallet.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 39,  96 => 37,  91 => 35,  81 => 30,  75 => 27,  71 => 26,  67 => 25,  61 => 22,  51 => 17,  44 => 13,  34 => 8,  29 => 6,  24 => 4,  19 => 1,);
    }
}
/*  <div class="row ">*/
/*   <div class="col-lg-3  col-md-3 col-sm-6">*/
/*     <div class="tile tile-primary">*/
/*       <div class="tile-heading">{{ heading_title1 }} </div>*/
/*       <div class="tile-body"><i class="fa fa-exchange"></i>*/
/*         <h2 class="pull-right">{{ totaltrasaction }}</h2>        */
/*       </div>*/
/*       <div class="tile-footer"><a href="{{ e_wallet }}">{{ text_view }}</a></div>*/
/*     </div>      */
/*   </div>*/
/*   <div class="col-lg-3  col-md-3 col-sm-6">*/
/*     <div class="tile tile-primary">*/
/*       <div class="tile-heading">{{ heading_title3 }} </div>*/
/*       <div class="tile-body"><i class="fa fa-plus-square-o "></i>*/
/*         */
/*       </div>*/
/*       <div class="tile-footer"><a href="{{ add }}">{{ text_view }}</a></div>*/
/*     </div> */
/*   </div>*/
/*   <div class="col-lg-3  col-md-3 col-sm-6">*/
/*     <div class="tile tile-primary">*/
/*       <div class="tile-heading">{{ heading_title4 }} </div>*/
/*       <div class="tile-body"><i class="fa fa-credit-card"></i>*/
/*       <div class="pull-right" style="line-height: 25px;">        */
/*         <div >{{ pendding }}</div>*/
/*         <div >{{ approve }}</div>*/
/*         <div >{{ reject }}</div>*/
/*       </div>*/
/*       </div>*/
/*       <div class="tile-footer"><a href="{{ money }}">{{ text_view }}</a></div>*/
/*     </div> */
/*   </div>*/
/*   <div class="col-lg-3  col-md-3 col-sm-6">*/
/*     <div class="tile tile-primary">*/
/*       <div class="tile-heading">{{ heading_title2 }} </div>*/
/*       <div class="tile-body"><i class="fa fa-money   "></i>*/
/*         <h2 class="pull-right">{{ balance }}</h2>*/
/*       </div>*/
/*       <div class="tile-footer"><a href="{{ customer }}">{{ text_view }}</a></div>*/
/*     </div> */
/*   </div>*/
/* </div>*/
/* */
/* */
