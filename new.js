import Treeselect from './dist/treeselect-js.js';

const options = [
  {
    name: 'JavaScript',
    value: 'JavaScript',
    children: [
      {
        name: 'React',
        value: 'React',
        children: [
          {
          name: 'React.js',
          value: 'React.js',
          children: []
          },
          {
          name: 'React Native',
          value: 'React Native',
          children: []
          }
        ]
      },
      {
        name: 'Vue',
        value: 'Vue',
        children: []
      }
      ,
      {
        name: 'Angular',
        value: 'Angular',
        children: []
      }
    ]
  },
  {
    name: 'HTML',
    value: 'html',
    children: [
      {
        name: 'HTML5',
        value: 'HTML5',
        children: []
      },
      {
        name: 'XML',
        value: 'XML',
        children: []
      }
    ]
  }
]

const slot = document.createElement('div')
slot.innerHTML='<a class="test" href="">Add new element</a>'

const domEl = document.querySelector('.treeselect-test')
const treeselect = new Treeselect({
  parentHtmlContainer: domEl,
  value: [],
  options: options,
  alwaysOpen: false,
  appendToBody: true,
  listSlotHtmlComponent: null,
  disabled: false,
  emptyText: 'No data text'
})

treeselect.srcElement.addEventListener('input', (e) => {
  console.log(e.detail)
})
