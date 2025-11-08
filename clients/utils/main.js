const template = document.getElementById('sidebar-template');
const container = document.getElementById('sidebar-container');

const clone = template.content.cloneNode(true);
container.appendChild(clone);