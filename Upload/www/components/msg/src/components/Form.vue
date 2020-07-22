<template>
  <div class="form flex-9">
    
    
    <form ref="form" @submit="checkForm"
      action="index.php?page=MessageCompose&action=send"
      method="post" enctype="multipart/form-data" id="composeForm">

      <input type="hidden" name="recipients" v-model="messageRecipients">
      <input type="hidden" name="ccrecipients" value="">
      <input type="hidden" name="bccrecipients" value="">
      <input type="hidden" name="messageSubject" v-model="messageSubject">
      <input type="hidden" name="messageText" value="">
      <input type="hidden" name="priority" value="">
      <input type="hidden" name="attachments" value="">
      <input type="hidden" name="questions" value="">
      <input type="hidden" name="readConfirmation" value="">
      <input type="hidden" name="forwardMessage" value="">
      <input type="hidden" name="replyMessage" value="">
      <input type="hidden" name="replyAllMessage" value="">
      <input type="hidden" name="dontAllowAnser" value="">

      <textarea class="hidden" name="messageText" v-model="messageText"></textarea>
    </form>

    <div class="bar margin-b-l">
      <button @click="clickHandlerCloseForm()" class="btn btn-grau margin-r-xs"><i class="fa fa-arrow-left"></i></button>
      <button @click="clickHandlerClearForm()" class="btn btn-grau margin-r-xs">Abbrechen</button>
      <button @click="clickHandlerSubmitForm()" class="btn btn-blau margin-r-xs"><i class="fa fa-paper-plane"></i>Senden</button>
      
    </div>

    <ul class="margin-b-m">
      <li class="flex-row margin-b-s">
        <label class="flex-1">Empfänger:</label>
        <div class="flex-6 flex-row">
          <FormRecipient v-show="openRecipients" type="recipients"></FormRecipient>
          <button @click="clickHandlerRecipients()" v-show="!openRecipients" class="btn btn-grau margin-r-s" ><i class="fa fa-plus"></i></button>
          <ul v-show="!openRecipients">
            <li v-bind:key="index" v-for="(item, index) in messageRecipientsArray">{{item.name}}</li>
          </ul>
        </div>
      </li>
      <li class="flex-row margin-b-s">
        <label class="flex-1">Kopieempfänger cc:</label>
        <div class="flex-6 flex-row">
          <FormRecipient v-show="openCcRecipients" type="ccrecipients"></FormRecipient>
          <button @click="clickHandlerCcRecipients()" v-show="!openCcRecipients" class="btn btn-grau margin-r-s" ><i class="fa fa-plus"></i></button>
          <ul v-show="!openCcRecipients">
            <li v-bind:key="index" v-for="(item, index) in messageCcRecipientsArray">{{item.name}}</li>
          </ul>
        </div>
      </li>
      <li class="flex-row margin-b-s">
        <label class="flex-1">Verdeckte Kopieempfänger bcc:</label>
        <div class="flex-6 flex-row">
          <FormRecipient v-show="openBccRecipients" type="bccrecipients"></FormRecipient>
          <button @click="clickHandlerBccRecipients()" v-show="!openBccRecipients" class="btn btn-grau margin-r-s" ><i class="fa fa-plus"></i></button>
          <ul v-show="!openBccRecipients">
            <li v-bind:key="index" v-for="(item, index) in messageBccRecipientsArray">{{item.name}}</li>
          </ul>
        </div>
      </li>
    </ul>

    <ul>
      <li class="flex-row margin-b-m">
        <label class="flex-1">Betreff:</label>
        <input class="flex-6" type="text" v-model="messageSubject" />
      </li>
      <li class="flex-row">
        <label class="flex-1">Nachricht:</label>
        <textarea class="flex-6" v-model="messageText"></textarea>
      </li>
    </ul>
    

  </div>
</template>

<script>

import FormRecipient from './FormRecipient.vue'

const axios = require('axios').default;

export default {
  name: 'Form',
  components: {
    FormRecipient
  },
  props: {
    //messages: Array
  },
  data: function () {
    return {

      messageText: '',
      messageSubject: '',
      
      

      openRecipients: false,
      messageRecipientsArray: [],
      messageRecipients: '',

      openCcRecipients: false,
      messageCcRecipientsArray: [],
      messageCcRecipients: '',

      openBccRecipients: false,
      messageBccRecipientsArray: [],
      messageBccRecipients: '',

    }
  },
  computed: {
    

  },
  
  created: function () {


    EventBus.$on('message--form--set-recipient', data => {

      if (data.type == 'recipients') {
        this.messageRecipients = data.recipientsString;
        this.messageRecipientsArray = data.recipientsArray;
        this.openRecipients = false;

      } else if (data.type == 'ccrecipients') {
        this.messageCcRecipients = data.recipientsString;
        this.messageCcRecipientsArray = data.recipientsArray;
        this.openCcRecipients = false;

      } else if (data.type == 'bccrecipients') {
        this.messageBccRecipients = data.recipientsString;
        this.messageBccRecipientsArray = data.recipientsArray;
        this.openBccRecipients = false;
      }

    });



  },
  methods: {

    checkForm: function ($event) {

      $event.preventDefault();

    },
    clickHandlerSubmitForm : function(){
      this.$refs.form.submit()
    },

    clickHandlerCloseForm: function () {

      EventBus.$emit('message--form--close', {
      })

    },

    clickHandlerClearForm: function () {

      this.messageText = '';
      this.messageSubject = '';
      
      this.openRecipients = false;
      this.messageRecipientsArray = [];
      this.messageRecipients = '';

      this.openCcRecipients = false;
      this.messageCcRecipientsArray = [];
      this.messageCcRecipients = '';

      this.openBccRecipients = false;
      this.messageBccRecipientsArray = [];
      this.messageBccRecipients = '';

      this.clickHandlerCloseForm();


    },



    clickHandlerRecipients: function () {
      this.openRecipients = true;
    },
    clickHandlerCcRecipients: function () {
      this.openCcRecipients = true;
    },
    clickHandlerBccRecipients: function () {
      this.openBccRecipients = true;
    }
 

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>


</style>
