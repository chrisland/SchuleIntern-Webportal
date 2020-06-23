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

    <div class="bar margin-b-m">
      <button @click="clickHandlerSubmitForm()" class="btn btn-blau"><i class="fa fa-paper-plane"></i>Senden</button>
      <button @click="clickHandlerCloseForm()" class="btn btn-blau">Abbrechen</button>
    </div>

    <ul>
      <li class="flex-row border-b">
        <label class="flex-1">Empfänger:</label>

        
        <div class="flex-8">
          <FormRecipient v-show="openRecipients"></FormRecipient>
          <button @click="clickHandlerRecipients()" class="btn btn-grau" ><i class="fa fa-plus"></i></button>

          <ul>
            <li v-bind:key="index" v-for="(item, index) in messageRecipientsArray">{{item.name}}</li>
          </ul>
          
        </div>
      </li>
      <li>
        <label>Kopieempfänger cc:</label>
        <button class="btn btn-grau" ><i class="fa fa-plus"></i></button>
      </li>
      <li>
        <label>Verdeckte Kopieempfänger bcc:</label>
        <button class="btn btn-grau" ><i class="fa fa-plus"></i></button>
      </li>
      <li>
        <label>Betreff</label>
        <input type="text" v-model="messageSubject" />
      </li>
      <li>
        <label>Nachricht</label>
        <textarea  v-model="messageText"></textarea>
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
      messageRecipients: '',
      messageRecipientsArray: [],

      openRecipients: false

    }
  },
  computed: {
    

  },
  
  created: function () {


    EventBus.$on('message--form--set-recipient', data => {

      //if (data.recipients) {
        this.messageRecipients = data.recipientsString;
        this.messageRecipientsArray = data.recipientsArray;
      //}
      

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
        //folder: item,
      })

    },



    




    clickHandlerRecipients: function () {

      this.openRecipients = true;
      
    }
 

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
