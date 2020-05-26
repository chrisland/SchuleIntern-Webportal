<template>
  <div class="form flex-9">
    
    
    <ul>
      <li>
        Empfänger:
        <button @click="clickHandlerRecipients()" >add</button>

        <div class="flex-row height_35">

          <div class="flex-1">
            <button @click="clickHandlerRecipientsTabOpen('tab-1')">Allgemein</button>
            <button @click="clickHandlerRecipientsTabOpen('tab-2')">Lehrer</button>
            <button>Schüler</button>
            <button>Eltern</button>
          </div>
          <div class="flex-1">
            <div class="tab" v-show="recipientsTabOpen == 'tab-1'">
              <button>Schulleitung</button>
              <button>Sekretariat</button>
              <button>OGS</button>
            </div>
            <div class="tab" v-show="recipientsTabOpen == 'tab-2'">
              <button @click="clickHandlerRecipientsLehrer()">Lehrer</button>
              <button @click="clickHandlerRecipientsFachschaft()">Fachschaft</button>
              <button>Klassenlehrer</button>
              <button>Klassenleitungen</button>
            </div>
          </div>
          <div class="flex-1" >

            <button v-bind:key="index" v-for="(item, index) in recipientsResult"
              @click="clickHandlerRecipientsSelect(item)"
              v-bind:class="isRecipientSelected(item)">{{item.text}}{{item.id}}</button>

          </div>
          <div class="flex-1">

            {{recipientsSelect}}

            <button v-bind:key="index" v-for="(item, index) in recipientsSelect"
              @click="clickHandlerRecipientsSelect(item)"
              v-bind:class="isRecipientSelected(item)">{{item.text}}{{item.id}}</button>

          </div>

        </div>

      </li>
      <li>
        Kopieempfänger cc:
        <button>add</button>
      </li>
      <li>
        Verdeckte Kopieempfänger bcc:
        <button>add</button>
      </li>
    </ul>

    betreff:
    <input type="text" v-model="messageSubject" />

    text:
    <textarea  v-model="messageText"></textarea>

    
<hr>


    <form action="index.php?page=MessageCompose&action=send"
      method="post" enctype="multipart/form-data" id="composeForm">

      <input type="text" name="recipients" v-model="recipientsSelectString">
      <input type="text" name="ccrecipients" value="">
      <input type="text" name="bccrecipients" value="">
      <input type="text" name="messageSubject" v-model="messageSubject">
      <input type="text" name="messageText" value="">
      <input type="text" name="priority" value="">
      <input type="text" name="attachments" value="">
      <input type="text" name="questions" value="">
      <input type="text" name="readConfirmation" value="">
      <input type="text" name="forwardMessage" value="">
      <input type="text" name="replyMessage" value="">
      <input type="text" name="replyAllMessage" value="">
      <input type="text" name="dontAllowAnser" value="">

      <textarea name="messageText" v-model="messageText"></textarea>


      <button>Nachricht senden</button>
    </form>

  </div>
</template>

<script>

//import GridTemplate from './GridTemplate.vue'

const axios = require('axios').default;

export default {
  name: 'Form',
  components: {
    //GridTemplate
  },
  props: {
    //messages: Array
  },
  data: function () {
    return {

      messageText: '',
      messageSubject: '',
      
      recipientsSelectString: '',
      recipientsSelect: [],
      recipientsResult: [],

      recipientsTabOpen: 'tab-1'

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

    isRecipientSelected: function (item) {

      for (var i = 0, len = this.recipientsSelect.length; i < len; i++) {
        if ( this.recipientsSelect[i].key == item.id) {
          return 'btn-success';
        }
      }
      return false;

    },
    
    clickHandlerRecipientsSelect: function (recipient) {
      

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

    clickHandlerRecipients: function () {

      
    }
 

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
