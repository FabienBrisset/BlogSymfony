<?php

/* FOSUserBundle:Registration:register.html.twig */
class __TwigTemplate_6fff60e8bf91c650a4da3b849b157644ae705829857a123de1710eae414ae203 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("FOSUserBundle::layout.html.twig", "FOSUserBundle:Registration:register.html.twig", 1);
        $this->blocks = array(
            'fos_user_content' => array($this, 'block_fos_user_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FOSUserBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_23346d96a3b0b69346555144226996f792c424ee93d18f4df45152165f9dac44 = $this->env->getExtension("native_profiler");
        $__internal_23346d96a3b0b69346555144226996f792c424ee93d18f4df45152165f9dac44->enter($__internal_23346d96a3b0b69346555144226996f792c424ee93d18f4df45152165f9dac44_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "FOSUserBundle:Registration:register.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_23346d96a3b0b69346555144226996f792c424ee93d18f4df45152165f9dac44->leave($__internal_23346d96a3b0b69346555144226996f792c424ee93d18f4df45152165f9dac44_prof);

    }

    // line 3
    public function block_fos_user_content($context, array $blocks = array())
    {
        $__internal_02ebae31db886757918185eaccecb648aa8c76314bdf9554682c33ef7c35acde = $this->env->getExtension("native_profiler");
        $__internal_02ebae31db886757918185eaccecb648aa8c76314bdf9554682c33ef7c35acde->enter($__internal_02ebae31db886757918185eaccecb648aa8c76314bdf9554682c33ef7c35acde_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "fos_user_content"));

        // line 4
        $this->loadTemplate("FOSUserBundle:Registration:register_content.html.twig", "FOSUserBundle:Registration:register.html.twig", 4)->display($context);
        
        $__internal_02ebae31db886757918185eaccecb648aa8c76314bdf9554682c33ef7c35acde->leave($__internal_02ebae31db886757918185eaccecb648aa8c76314bdf9554682c33ef7c35acde_prof);

    }

    public function getTemplateName()
    {
        return "FOSUserBundle:Registration:register.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  40 => 4,  34 => 3,  11 => 1,);
    }
}
/* {% extends "FOSUserBundle::layout.html.twig" %}*/
/* */
/* {% block fos_user_content %}*/
/* {% include "FOSUserBundle:Registration:register_content.html.twig" %}*/
/* {% endblock fos_user_content %}*/
/* */
