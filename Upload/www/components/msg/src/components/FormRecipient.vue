<template>
  <div class="flex-row height_35 bg-white">
    
    <div class="flex-1 flex">
      <button @click="clickHandlerRecipientsTabOpen('tab-1')">Allgemein</button>
      <button @click="clickHandlerRecipientsTabOpen('tab-2')">Lehrer</button>
      <button>Schüler</button>
      <button>Eltern</button>
    </div>
    <div class="flex-1">
      <div class="tab flex" v-show="recipientsTabOpen == 'tab-1'">
        <button>Schulleitung</button>
        <button>Sekretariat</button>
        <button>OGS</button>
      </div>
      <div class="tab flex" v-show="recipientsTabOpen == 'tab-2'">
        <button @click="clickHandlerRecipientsLehrer()">Lehrer</button>
        <button @click="clickHandlerRecipientsFachschaft()">Fachschaft</button>
        <button>Klassenlehrer</button>
        <button>Klassenleitungen</button>
      </div>
    </div>
    <div class="flex-1 flex" >

      <button v-bind:key="index" v-for="(item, index) in recipientsResult"
        @click="clickHandlerRecipientsSelect(item)"
        v-bind:class="isRecipientSelected(item)">{{item.text}}</button>

    </div>
    <div class="flex-1 flex">

      <button v-bind:key="index" v-for="(item, index) in recipientsSelect"
        @click="clickHandlerRecipientsSelect(item)"
        v-bind:class="isRecipientSelected(item)">{{item.name}}</button>

    </div>
    
  </div>
</template>

<script>

//import GridTemplate from './GridTemplate.vue'

const axios = require('axios').default;

export default {
  name: 'FormRecipient',
  components: {
    //GridTemplate
  },
  props: {
    //messages: Array
    recipientsSelectString: String
  },
  data: function () {
    return {

      recipientsTabOpen: 'tab-1',

      //recipientsSelectString: '',
      recipientsSelect: [],
      recipientsResult: [],

    }
  },
  computed: {
    

  },
  
  created: function () {

  },
  methods: {

    clickHandlerRecipientsTabOpen: function (tab) {

      this.recipientsTabOpen = tab;

    },
    clickHandlerRecipientsLehrer: function () {

      var that = this;

      axios.get('index.php?page=MessageCompose&action=getTeachersJSON&_type=query', {
        params: {}
      })
      .then(function (response) {
        // console.log(response.data);
        if (response.data.results) {
          that.recipientsResult = response.data.results;
        }
      })
      .catch(function (resError) {
        console.log(resError);
      })

    },
    clickHandlerRecipientsFachschaft: function () {

      this.recipientsResult = [];


    },
    clickHandlerRecipientsSelect: function (recipient) {
      
      if (!recipient.id && !recipient.key) {
        return false;
      }
      if (recipient.key) {
        recipient.id = recipient.key;
        recipient.text = recipient.name;
      }

      var found = false;
      for (var i = 0, len = this.recipientsSelect.length; i < len; i++) {
        if ( this.recipientsSelect[i].key == recipient.id) {
          found = this.recipientsSelect[i];
        }
      }

      if (found) {

        const index = this.recipientsSelect.indexOf(found);
        if (index > -1) {
          this.recipientsSelect.splice(index, 1);
        }

      } else {
        this.recipientsSelect.push({
          key: recipient.id,
          name: recipient.text
        });
      }

      var list = '';
      for(i = 0; i < this.recipientsSelect.length; i++) {
        if(i > 0) list += ";";
        list += this.recipientsSelect[i]['key'];
      }
      this.recipientsSelectString = list;
      //this.messageRecipients = list;


      EventBus.$emit('message--form--set-recipient', {
        recipientsString: this.recipientsSelectString,
        recipientsArray: this.recipientsSelect
      })


    },
    isRecipientSelected: function (item) {

      for (var i = 0, len = this.recipientsSelect.length; i < len; i++) {
        if ( this.recipientsSelect[i].key == item.id) {
          return 'btn-success';
        }
      }
      return false;

    },



  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
