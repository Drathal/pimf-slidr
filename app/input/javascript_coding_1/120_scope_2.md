# Techtalk / Javascript Coding Guidelines
### Scope

[script js]
var a = 1; 
function globalScope(){ 
  console.log(this.a); 
}
[/script]

<pre>[a bounceInRight]globalScope() : 1</pre>
<pre>[a bounceInRight]new globalScope() : undefined</pre>
<pre>[a bounceInRight]console.log(window.a) : 1</pre>


