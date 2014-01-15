# Techtalk / Javascript Coding Guidelines
### Scope

[script js]
var a = 1; 
function globalScope(){ 
  a = a + 1; // test
  console.log(a); 
}
[/script]

[a flipInY] Die Variable [color green]a[/color] wurde im globalen Scope definiert und ist somit auch innerhalb der Funktion [color green]globalSope[/color] verfügbar. Bei Aufruf der Funktion [color green]globalSope()[/color] wird [color green]2[/color] an die Console übergeben.
<pre>[a bounceInRight]globalScope() : 2</pre>

[a flipInX] In Zeile [color green]Zeile 3[/color] wird die globale Variable um 1 erhöht.
<pre>[a bounceInRight]console.log(a) : 2</pre>

[a flipInX] Die globale Varibale steht auch im window Object des Browsers zur verfügung.
<pre>[a bounceInRight]console.log(window.a) : 2</pre>








