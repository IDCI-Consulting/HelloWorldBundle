function buildNavMenu () {
  var navMenu = new Array();
  var headers = document.querySelectorAll('.blog-article h2, .blog-article h3');

  var count = 0;

  headers.forEach(function (element) {
    var lvl = element.nodeName.replace('H','');
    var cont = element.textContent;
    var id = slugify(cont);

    element.id = id;
    navMenu[id] = {level: lvl, content: cont};
  });

  var ul = document.createElement('ul');
  ul.className = "show-for-large-up aside-navigation-menu article-navigation";

  var url = window.location.hash.substring(1);

  for (var key in navMenu) {
    if (navMenu.hasOwnProperty(key)) {

      var li = document.createElement('li');
      var a = document.createElement('a');

      li.className = "list-level-" + navMenu[key].level;

      a.href = "#" + key;
      a.innerHTML = navMenu[key].content;
      a.className = "aside-navigation-link";

      li.appendChild(a);
      ul.appendChild(li);
    }
  }

  var row = document.getElementsByClassName('row')[0];
  if (row) {
    row.insertBefore(ul, row.firstChild);
  }

  enableScroll();
}


function slugify (value) {
  var rExps= [
    {re:/[\xC0-\xC6]/g, ch:'A'},
    {re:/[\xE0-\xE6]/g, ch:'a'},
    {re:/[\xC8-\xCB]/g, ch:'E'},
    {re:/[\xE8-\xEB]/g, ch:'e'},
    {re:/[\xCC-\xCF]/g, ch:'I'},
    {re:/[\xEC-\xEF]/g, ch:'i'},
    {re:/[\xD2-\xD6]/g, ch:'O'},
    {re:/[\xF2-\xF6]/g, ch:'o'},
    {re:/[\xD9-\xDC]/g, ch:'U'},
    {re:/[\xF9-\xFC]/g, ch:'u'},
    {re:/[\xC7-\xE7]/g, ch:'c'},
    {re:/[\xD1]/g, ch:'N'},
    {re:/[\xF1]/g, ch:'n'}
  ];

  // Convert accented chars into alpha
  for(var i=0, len=rExps.length; i<len; i++) {
    value=value.replace(rExps[i].re, rExps[i].ch);
  }

  // 1) met en bas de casse
  // 2) remplace les espace par des tirets
  // 3) enleve tout les caratères non alphanumeriques
  // 4) enlève les doubles tirets
  return value.toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
    .replace(/\-{2,}/g,'-');
};
