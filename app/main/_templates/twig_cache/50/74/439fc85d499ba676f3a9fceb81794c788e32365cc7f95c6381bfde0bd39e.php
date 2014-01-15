<?php

/* compile.phtml */
class __TwigTemplate_5074439fc85d499ba676f3a9fceb81794c788e32365cc7f95c6381bfde0bd39e extends Twig_Template
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
        echo twig_escape_filter($this->env, (isset($context["blog_title"]) ? $context["blog_title"] : null), "html", null, true);
        echo "</title>
    <style type=\"text/css\">
        @import url(http://fonts.googleapis.com/css?family=Lobster);
        body {
            font-size   : 12pt;
            color       : #666;
            line-height : 28px;
        }
        a, a:visited {
            color : #2972A2;
        }
        a:hover {
            color : #6ed347;
        }
        h1{
          font-family: 'Lobster', cursive;
          font-weight: bold;
        }
        h2{
          color: #2972A2;
        }
        footer{
          font-size   : 10pt;
        }
   \t</style>
</head>
<body>
  <header>
      <h1>";
        // line 33
        echo twig_escape_filter($this->env, (isset($context["blog_title"]) ? $context["blog_title"] : null), "html", null, true);
        echo "</h1>
  </header>
  <hr/>
  <h2>";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["blog_content"]) ? $context["blog_content"] : null), "html", null, true);
        echo "</h2>
  <hr/>
  <footer>
        <p>
          <strong>";
        // line 40
        echo twig_escape_filter($this->env, (isset($context["blog_footer"]) ? $context["blog_footer"] : null), "html", null, true);
        echo "</strong>
          <br />
          Now that you're up and running, it's time to start creating!
          Here are some links to help you get started:
        </p>

        <ul class=\"out-links\">
          <li><a href=\"http://pimf-framework.de\">Official Website</a></li>
          <li><a href=\"https://github.com/gjerokrsteski/pimf-framework/wiki\">Learning PIMF</a></li>
        </ul>
  </footer>
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
        return array (  69 => 40,  62 => 36,  56 => 33,  25 => 5,  19 => 1,);
    }
}
