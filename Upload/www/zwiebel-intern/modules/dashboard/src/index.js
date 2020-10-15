

// Vue.component('app', {
//   data() {
//     return {
//       items: [1,2,3],
//       val: ''
//     }
//   },
//   mounted() {
//     console.log('------vue start');
//   },
//   methods: {
//     handlerClick: function () {
//       this.$refs.b.innerHTML = '2';
//     }
//   },

// });


// var vm = new Vue({
//   el: '#app'
// });

console.log(Vue);
const app = Vue.createApp({
  //template: '#my-comp-template',
  data() {
    return { count: 4 }
  },
  mounted() {
    consolee.log('jo vue---');
  },
  render() {
    consolee.log('jo vue---');
    return Vue.h('div', {}, this.hi)
  }
})