<?php

/* compile.phtml */
class __TwigTemplate_c068ef7d397e393663874f92ccf22cb49a6c3ff8e038bc74404c2046a0b64583 extends Twig_Template
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
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <title>";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
    <style type=\"text/css\">
        body {
            font-family: 'Arial', sans-serif;
            font-size   : 12px;
            color       : #444;
            line-height : 16px;
        }
        a, a:visited {
            color : #2972A2;
        }
        a:hover {
            color : #6ed347;
        }
        h2{
          color: #2972A2;
        }
   \t</style>
</head>
<body>
  <header>
      <h1>";
        // line 26
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</h1>
  </header>
  <hr/>
  <h2>";
        // line 29
        echo twig_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo "</h2>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "compile.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 29,  49 => 26,  25 => 5,  19 => 1,);
    }
}
