<template>
  <div id="app" class="flex form-style">
    

    <div v-show="spinner" class="spinner">Wird geladen...</div>

    <div v-show="errorMsg" id="msg-error" class="callout callout-danger" >
      {{errorMsg}}
    </div>
    <div v-show="show.list" class="list flex-row"
      :class="{
        'height_35' : show.preview
      }">
      <Folders v-bind:folders="folders"></Folders>
      <Messages v-bind:messages="messages" v-bind:folders="folders" v-bind:foldersFilterMove="foldersFilterMove" v-bind:activeFolder="activeFolder"></Messages>
    </div>
    <div v-show="show.preview" class="preview flex-row">
      <Bar></Bar>
      <Message v-bind:message="message"></Message>
    </div>

    <div v-show="show.form">
      <Form></Form>
    </div>

    <div v-show="show.settings">
      <Settings></Settings>
    </div>

  </div>
</template>

<script>


//console.log('globals',globals);
// var globals = false;
// globals = globals || {
//   userID:  1608
// };

import Folders from './components/Folders.vue'
import Messages from './components/Messages.vue'
import Message from './components/Message.vue'
import Bar from './components/Bar.vue'
import Form from './components/Form.vue'
import Settings from './components/Settings.vue'

const axios = require('axios').default;

axios.defaults.headers.common['x-authorization'] = '112233' // for all requests

export default {
  name: 'app',
  components: {
    Folders,
    Messages,
    Message,
    Bar,
    Form,
    Settings
  },
  data: function () {
    return {
      folders: {},
      foldersFilterMove: [],
      messages: [],
      message: {},
      errorMsg: false,
      show: {
        list: true,
        preview: false,
        form: false,
        settings: false
      },
      spinner: false,

      activeFolder: false,
      handlerClickList: []
    }
  },
  created: function () {


    var that = this;

    EventBus.$on('message--move', data => {
      
      if (!data.toFolder.folderName && !data.toFolder.folderID) {
        return false;
      }

      var url = 'rest.php/PostMsgMessageMove/'+globals.userID+'/'+encodeURIComponent(data.toFolder.folderName)+'/'+data.toFolder.folderID;
      that.ajaxPost(
        url,
        {
          list: JSON.stringify(this.handlerClickList)
        },
        {},
        function (response, that) {
          //console.log(response.data);

          if (response.data.done == true) {

            for (var i = 0; i < that.messages.length; i++) {
              for (var j = 0; j < that.handlerClickList.length; j++) {
                if (that.messages[i].id == that.handlerClickList[j].id) {
                  var index = that.messages.indexOf(that.handlerClickList[j]);
                  that.messages.splice(index, 1);
                }
              }
            }

          } else {
            that.errorMsg = 'Beim Verschieben ist leider ein Fehler aufgetreten. (Code:Ajax Move Message 404)'
          }

        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Move Message 404)'
        }
      );


    });

    EventBus.$on('message--delete', data => {

      var url = 'rest.php/DeleteMsgMessage/'+globals.userID+'/'+this.activeFolder.folderName+'/'+this.activeFolder.folderID;
      that.ajaxPost(
        url,
        {
          list: JSON.stringify(this.handlerClickList)
        },
        {},
        function (response, that) {
          //console.log(response.data);

          if (response.data.done == true) {

            for (var i = 0; i < that.messages.length; i++) {
              for (var j = 0; j < that.handlerClickList.length; j++) {
                if (that.messages[i].id == that.handlerClickList[j].id) {
                  var index = that.messages.indexOf(that.handlerClickList[j]);
                  that.messages.splice(index, 1);
                }
              }
            }

          } else {
            that.errorMsg = 'Beim Löschen ist leider ein Fehler aufgetreten. (Code:Ajax Delete Message 404)'
          }

        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Delete Message 404)'
        }
      );


    });

    EventBus.$on('message--list', data => {
      this.handlerClickList = data.list;
    });

    EventBus.$on('message--read', data => {

      var url = 'rest.php/GetMsgMessageRead/'+globals.userID;
      if (data.message.id) {
        url += '/'+parseInt(data.message.id)
      }
      that.ajaxGet(
        url,
        {},
        function (response, that) {
          
          console.log(response);

          if (response.data.done == true) {

              for (var j = 0; j < that.handlerClickList.length; j++) {
                if (data.message.id == that.handlerClickList[j].id) {
                  console.log(that.handlerClickList[j]);
                }
              }
            

          } else {
            that.errorMsg = 'Beim Markieren ist leider ein Fehler aufgetreten. (Code:Ajax Message Read 2 404)'
          }

        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Message Read 404)'
        }
      );
    
    });

    EventBus.$on('message--open', data => {

      var url = 'rest.php/GetMsgMessage/'+globals.userID;
      if (data.message.id) {
        url += '/'+parseInt(data.message.id)
      }
      that.ajaxGet(
        url,
        {},
        function (response, that) {
          that.message = response.data;
          that.show.list = true;
          that.show.preview = true;
          that.show.form = false;

          // Set Message Read
          if (that.message.isRead == 0) {
            setTimeout(function () {
              EventBus.$emit('message--read', {
                message: that.message
              });
            }, 3000);
            
          }

          
        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Message 404)'
        }
      );

    });

    EventBus.$on('message--close', data => {

      that.message = false;
      that.show.list = true;
      that.show.preview = false;
      that.show.form = false;
      
    });


    EventBus.$on('folders--get', data => {

      that.ajaxGet(
        'rest.php/GetMsgFolders/'+globals.userID,
        {},
        function (response, that) {
          that.folders = response.data;

          for (const item in that.folders) {
            if ( that.folders[item].folderName != "Gesendete" ) {
              that.foldersFilterMove.push( that.folders[item] );
            }
            
          }
          
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

      this.activeFolder = data.folder;

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
            that.show.form = false;
          } else {
            that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Data)'
          }
        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Change Folders 404)'
        }
      );

    });


    EventBus.$on('message--form', data => {

      var url = 'rest.php/GetMsgForm/'+globals.userID;
      that.ajaxGet(
        url,
        {},
        function (response, that) {
          if (response.data) {

            that.show.list = false;
            that.show.preview = false;
            that.show.form = true;
            that.message = {};

            
            //console.log('rest.php/GetMsgForm/',response.data);
            
          } else {
            that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Form Data)'
          }

        },
        function (error) {
          that.errorMsg = 'Es ist leider ein Fehler aufgetreten. (Code:Ajax Open Form 404)'
        }
      );
      

    });

    EventBus.$on('message--form--close', data => {
      //console.log('close form');
      this.show.list = true;
      this.show.preview = false;
      this.show.form = false;
    });

    EventBus.$on('settings--open', data => {
      that.show.settings = true;
      that.show.list = false;
      that.show.preview = false;
      that.show.form = false;
    });
    
    EventBus.$on('settings--close', data => {
      that.show.settings = false;
      that.show.list = true;
      that.show.preview = false;
      that.show.form = false;
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

    
    ajaxGet: function (url, params, callback, error, allways) {

      this.spinner = true;
      var that = this;

      axios.get(url, {
        params: params
      })
      .then(function (response) {
        // console.log(response.data);
        if (callback && typeof callback === 'function') {
          that.spinner = false;
          callback(response, that);
        }
      })
      .catch(function (resError) {
        //console.log(error);
        if (resError && typeof error === 'function') {
          that.spinner = false;
          error(resError);
        }
      })
      .finally(function () {
        // always executed
        if (allways && typeof allways === 'function') {
          allways();
        }
      });  
      
    },
    ajaxPost: function (url, data, params, callback, error, allways) {

      var that = this;

      axios.post(url, data, {
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
