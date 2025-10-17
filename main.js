// small interactions
document.addEventListener('DOMContentLoaded', function(){
  var heroApply = document.getElementById('heroApply');
  if(heroApply){
    heroApply.addEventListener('mouseenter', function(){ this.classList.add('hovered'); });
    heroApply.addEventListener('mouseleave', function(){ this.classList.remove('hovered'); });
  }
});