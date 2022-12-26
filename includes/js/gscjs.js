const GSCJS = {
  /**
   * Selecciona un solo elemento en le documeneto
   * @param {nodeHTML} element
   * @returns {NodeHTML}
   */
  queryOnly: function queryOnly(element) {
    const selection = document.querySelector(element);
    return selection;
  },
  /**
   * Toma un nodeList del DOM y retorna un
   * Array con todos sus elementos.
   * 
   * @param {NodeList} element
   * @returns {Array}
   */
  queryAll: function queryAll(element) {
    const nodeList = document.querySelectorAll(element);
    const selection = [...nodeList];
    return selection;
  },
  /**
   * Esta funcion retorna un nodo HTML con su contenido.
   *
   * @param {string} tag Una etiqueta HTML
   * @param {string} txt El contenido de la etiqueta HTML
   * @returns {NodeHTML} Un nodo
   */
  crearNodo: function crearNodo(tag, txt) {
    const node = document.createElement(tag);
    node.textContent = txt;
    return node;
  },
  /**
   * Esta funcion retorna true si el nodo objetivo tiene la classe
   * espesificada y false si no tiene la clase.
   *
   * @param {NodeHTML} targetObjet El NodoHTML objeto de la verificación
   * @param {string} classname
   * @returns {boolean}
   */
  hasClass: function hasClass(target, classname) {
    const targetObjet = document.querySelector(target);
    const response = targetObjet.classList.contain(classname);
    return response;
  },
  // Retorna toda la URL
  getURL: function getURL() {
    const url = location.href;
    return url;
  },
  // Retorna solo la URL
  getURLorigin: function getURLorigin() {
    const url = location.origin;
    return url;
  },
  // Retorna la ruta del URL (lo que le sigue a la extención midominio.com/ruta)
  getURLpath: function getURLpath() {
    const url = location.pathname;
    return url;
  },
  // Retorna el hash
  getURLhash: function getURLhash() {
    let url = location.hash;
    if(url == ''){
      url = undefined;
    }
    return url;
  },
  /**
   * 
   * @returns documentfragment
   */
  crearFragmento: function fragmento (){
    const documentFragement = document.createDocumentFragment();
    return documentFragement;
  },
  getParameterByName : function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}



};


async function gscFetch (url, callback, mode){
  const get = await fetch(url);
  let response;
  switch (mode) {
    case "json":
      response = await get.json();
      break;
    case "blob":
      response = await get.blob();
      break;
    default:
      response = await get.text();
      break;
  }
  callback(response);
}

const gscConsole = (e) => console.log(e);
