<template>
  <div id="app" class="flex">
    <div v-show="errorMsg" id="msg-error" class="callout callout-danger" >
      {{errorMsg}}
    </div>
    <div v-show="show.list" class="list flex-row"
      :class="{
        'height_35' : show.preview
      }">
      <Folders v-bind:folders="folders"></Folders>
      <Messages v-bind:messages="messages"></Messages>
    </div>
    <div v-show="show.preview" class="preview flex-row">
      <Bar></Bar>
      <Message v-bind:message="message"></Message>
    </div>

    <div v-show="show.newMessage">
      <button @click="clickHandlerCloseNewMessage()">Close</button>
      <NewMessage></NewMessage>
    </div>
  </div>
</template>

<script>


//console.log('globals',globals);
var globals = false;
globals = globals || {
  userID:  1608
};

import Folders from './components/Folders.vue'
import Messages from './components/Messages.vue'
import Message from './components/Message.vue'
import Bar from './components/Bar.vue'
import NewMessage from './components/NewMessage.vue'

const axios = require('axios').default;

axios.defaults.headers.common['x-authorization'] = '112233' // for all requests

export default {
  name: 'app',
  components: {
    Folders,
    Messages,
    Message,
    Bar,
    NewMessage
  },
  data: function () {
    return {
      folders: {},
      messages: [],
      message: {},
      errorMsg: false,
      show: {
        list: true,
        preview: false,
        newMessage: false
      }
    }
  },
  created: function () {


    var that = this;

    EventBus.$on('message--open', data => {

      var url = 'rest.php/GetMsgMessage/'+globals.userID;
      if (data.message.id) {
        url += '/'+parseInt(data.message.id)
      }
      that.ajaxGet(
        url,
        //'./../testjson/GetMsgFolders.json',
        {},
        function (response, that) {
          that.message = response.data;
          that.show.list = true;
          that.show.preview = true;
          that.showNewMessage = false;
        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Message 404)'
        }
      );

    });


    EventBus.$on('folders--get', data => {

      that.ajaxGet(
        'rest.php/GetMsgFolders/'+globals.userID,
        //'./../testjson/GetMsgFolders.json',
        {},
        function (response, that) {
          that.folders = response.data;
        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Folders 404)'
        }
      );

    });

    EventBus.$on('messages--changeFolder', data => {

      //console.log('change msg folder',data.folder.folderName);

      var url = 'rest.php/GetMsgMessages/'+globals.userID;

      if (data.folder.isStandardFolder && !data.folder.folderID) {
        url += '/'+data.folder.folderName;
      } else {
        url += '/'+data.folder.folderID;
      }
      that.ajaxGet(
        url,
        //'./../testjson/GetMsgMessages.json',
        {},
        function (response, that) {
          //console.log(response);
          
          if (response.data) {
            //console.log(response.data);
            that.messages = response.data;
            that.message = {};
            that.show.list = true;
            that.show.preview = false;
            that.showNewMessage = false;
          } else {
            that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Data)'
          }
        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Change Folders 404)'
        }
      );

    });


    EventBus.$on('message--new', data => {

      var url = 'rest.php/GetMsgForm/'+globals.userID;
      that.ajaxGet(
        url,
        {},
        function (response, that) {
          if (response.data) {

            this.show.list = false;
            this.show.preview = false;
            this.show.newMessage = true;
            that.message = {};

            
            console.log(response.data);
            
          } else {
            that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Form Data)'
          }

        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Open Form 404)'
        }
      );
      

    });

    /*
       Init
    */
    EventBus.$emit('messages--changeFolder', {
      folder: {
        isStandardFolder: true,
        folderName: 'Posteingang',
        folderID: 0
      },
    })

    
  },
  methods: {

    clickHandlerCloseNewMessage: function () {
      this.show.list = true;
      this.show.preview = false;
      this.show.newMessage = false;
    },
    ajaxGet: function (url, params, callback, error, allways) {

      var that = this;

      axios.get(url, {
        params: params
      })
      .then(function (response) {
        // console.log(response.data);
        if (callback && typeof callback === 'function') {
          callback(response, that);
        }
      })
      .catch(function (resError) {
        //console.log(error);
        if (resError && typeof error === 'function') {
          error(resError);
        }
      })
      .finally(function () {
        // always executed
        if (allways && typeof allways === 'function') {
          allways();
        }
      });  
      
    }
  }
}
</script>

<style>


</style>
