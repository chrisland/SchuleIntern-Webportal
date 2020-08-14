(function(e){function t(t){for(var i,a,c=t[0],l=t[1],o=t[2],p=0,d=[];p<c.length;p++)a=c[p],Object.prototype.hasOwnProperty.call(n,a)&&n[a]&&d.push(n[a][0]),n[a]=0;for(i in l)Object.prototype.hasOwnProperty.call(l,i)&&(e[i]=l[i]);u&&u(t);while(d.length)d.shift()();return r.push.apply(r,o||[]),s()}function s(){for(var e,t=0;t<r.length;t++){for(var s=r[t],i=!0,c=1;c<s.length;c++){var l=s[c];0!==n[l]&&(i=!1)}i&&(r.splice(t--,1),e=a(a.s=s[0]))}return e}var i={},n={app:0},r=[];function a(t){if(i[t])return i[t].exports;var s=i[t]={i:t,l:!1,exports:{}};return e[t].call(s.exports,s,s.exports,a),s.l=!0,s.exports}a.m=e,a.c=i,a.d=function(e,t,s){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:s})},a.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var s=Object.create(null);if(a.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)a.d(s,i,function(t){return e[t]}.bind(null,i));return s},a.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/";var c=window["webpackJsonp"]=window["webpackJsonp"]||[],l=c.push.bind(c);c.push=t,c=c.slice();for(var o=0;o<c.length;o++)t(c[o]);var u=l;r.push([0,"chunk-vendors"]),s()})({0:function(e,t,s){e.exports=s("56d7")},"56d7":function(e,t,s){"use strict";s.r(t);s("cadf"),s("551c"),s("f751"),s("097d");var i=s("2b0e"),n=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"flex",attrs:{id:"app"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.errorMsg,expression:"errorMsg"}],staticClass:"callout callout-danger",attrs:{id:"msg-error"}},[e._v("\n    "+e._s(e.errorMsg)+"\n  ")]),s("div",{directives:[{name:"show",rawName:"v-show",value:e.show.list,expression:"show.list"}],staticClass:"list flex-row",class:{height_35:e.show.preview}},[s("Folders",{attrs:{folders:e.folders}}),s("Messages",{attrs:{messages:e.messages,folders:e.folders,foldersFilterMove:e.foldersFilterMove}})],1),s("div",{directives:[{name:"show",rawName:"v-show",value:e.show.preview,expression:"show.preview"}],staticClass:"preview flex-row"},[s("Bar"),s("Message",{attrs:{message:e.message}})],1),s("div",{directives:[{name:"show",rawName:"v-show",value:e.show.form,expression:"show.form"}]},[s("Form")],1)])},r=[],a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"folders flex-2"},[s("div",{staticClass:"toolbar"},[s("button",{staticClass:"btn btn-blau",on:{click:function(t){return e.clickHandlerNewMessage()}}},[e._v("Neue Nachricht")])]),s("ul",e._l(e.folders,(function(t,i){return s("li",{key:i},[s("button",{staticClass:"btn btn-grau text-align-left",class:{"btn-orange":e.foldersOpen==t.folderName,"margin-b-m":"Papierkorb"==t.folderName},on:{click:function(s){return e.clickHandlerFolder(t)}}},["Posteingang"==t.folderName?s("i",{staticClass:"fa fa-inbox"}):e._e(),"Gesendete"==t.folderName?s("i",{staticClass:"fa fa-envelope"}):e._e(),"Archiv"==t.folderName?s("i",{staticClass:"fa fa-archive"}):e._e(),"Papierkorb"==t.folderName?s("i",{staticClass:"fa fa-trash"}):e._e(),e._v("\n\n        "+e._s(t.folderName)+"\n      ")])])})),0)])},c=[],l={name:"Folders",props:{folders:Object},data:function(){return{foldersOpen:"Posteingang"}},created:function(){EventBus.$emit("folders--get",{})},methods:{clickHandlerNewMessage:function(){EventBus.$emit("message--form",{})},clickHandlerFolder:function(e){EventBus.$emit("messages--changeFolder",{folder:e}),this.foldersOpen=e.folderName}}},o=l,u=s("2877"),p=Object(u["a"])(o,a,c,!1,null,"105b85ac",null),d=p.exports,f=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"messages flex-10"},[s("div",{staticClass:"toolbar flex-row"},[s("div",{staticClass:"flex-9"},[s("button",{on:{click:function(t){return e.messageDelete()}}},[e._v("Löschen")]),s("button",{on:{click:function(e){}}},[e._v("Gelesen")]),s("button",{on:{click:function(e){}}},[e._v("Ungelesen")]),s("select",{directives:[{name:"model",rawName:"v-model",value:e.messageMoveSelected,expression:"messageMoveSelected"}],on:{change:function(t){var s=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){var t="_value"in e?e._value:e.value;return t}));e.messageMoveSelected=t.target.multiple?s:s[0]}}},e._l(e.foldersFilterMove,(function(t,i){return s("option",{key:i,domProps:{value:t}},[e._v(e._s(t.folderName))])})),0),s("button",{on:{click:function(t){return e.messageMove()}}},[e._v("Verschieben")])]),s("form",{staticClass:"3",attrs:{id:"search"}},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.searchQuery,expression:"searchQuery"}],attrs:{type:"search",name:"query"},domProps:{value:e.searchQuery},on:{input:function(t){t.target.composing||(e.searchQuery=t.target.value)}}})])]),s("GridTemplate",{attrs:{list:e.messages,columns:e.gridColumns,columsHeader:e.gridColumnsHeader,"filter-key":e.searchQuery}})],1)},m=[],h=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"gridtemplate"},[s("table",{staticClass:"noselect"},[s("thead",[s("tr",[s("td",[s("span",{on:{click:function(t){return e.selectAllToggle()}}},[e._v("Select all")])]),e._l(e.columns,(function(t,i){return s("td",{key:i,class:{active:e.sortKey==t}},[e.columsHeader[i]?s("span",{on:{click:function(s){return e.sortBy(t)}}},[e._v(e._s(e.columsHeader[i]))]):e._e(),s("i",{staticClass:"fa ",class:{"fa-sort-down":1==e.sortOrders[t],"fa-sort-up":-1==e.sortOrders[t]}})])}))],2)]),s("tbody",e._l(e.filteredlist,(function(t,i){return s("tr",{key:i},[s("td",[s("input",{attrs:{type:"checkbox"},domProps:{checked:t.selected},on:{click:function(s){return e.clickHandler(t,{shiftKey:!0})}}})]),e._l(e.columns,(function(i,n){return s("td",{key:n},["isRead"==i&&1==t[i]?s("i",{}):"isRead"==i&&0==t[i]?s("i",{staticClass:"fa fa-envelope"}):"priority"==i&&"NORMAL"==t[i]?s("i",{}):"priority"==i&&"HIGH"==t[i]?s("i",{staticClass:"fa fa-arrow-up text-red"}):"priority"==i&&"LOW"==t[i]?s("i",{staticClass:"fa fa-arrow-down text-green"}):"hasAttachment"==i&&t[i]?s("i",{staticClass:"fa fa-file-o"}):"hasAttachment"==i&&""==t[i]?s("i",{}):s("span",{on:{click:function(s){return e.clickHandler(t,s)}}},[e._v("\n            "+e._s(t[i])+"\n          ")])])}))],2)})),0)])])},v=[],g=(s("55dd"),s("ac6a"),s("456d"),{name:"GridTemplate",template:"#grid-template",props:{list:Array,columns:Array,columsHeader:Array,filterKey:String},data:function(){return{sortKey:"",sortOrders:{},clickHandlerList:[],clickHandlerNode:!1}},computed:{filteredlist:function(){var e=this.filterKey&&this.filterKey.toLowerCase(),t=parseInt(this.sortOrders[this.sortKey])||1,s=this.list;e&&(s=s.filter((function(t){return Object.keys(t).some((function(s){return String(t[s]).toLowerCase().indexOf(e)>-1}))})));var i=this;if(s){s=s.slice().sort((function(e,s){return"timeFormat"==i.sortKey?(e=e["messageTime"],s=s["messageTime"]):(e=e[i.sortKey],s=s[i.sortKey]),(e===s?0:e>s?1:-1)*t}));for(var n=0;n<s.length;n++)for(var r=0;r<this.clickHandlerList.length;r++)s[n].id==this.clickHandlerList[r].id&&(s.selected=!0)}return s}},filters:{capitalize:function(e){return e?e.charAt(0).toUpperCase()+e.slice(1):""}},methods:{selectAllToggle:function(){if(console.log("toggle",this.clickHandlerList.length),this.clickHandlerList.length>1){this.clickHandlerList=[];for(var e=0;e<this.filteredlist.length;e++)this.filteredlist[e].selected=!1}else this.clickHandlerList=this.filteredlist},clickHandler:function(e,t){if(!e)return!1;if(t.shiftKey){for(var s=!1,i=0;i<this.clickHandlerList.length;i++)if(this.clickHandlerList[i].id==e.id){this.clickHandlerList[i].selected=!1;var n=this.clickHandlerList.indexOf(this.clickHandlerList[i]);this.clickHandlerList.splice(n,1),s=!0}0==s&&(e.selected=!0,this.clickHandlerList.push(e)),EventBus.$emit("message--close",{})}else{if(this.clickHandlerList)for(i=0;i<this.clickHandlerList.length;i++)this.clickHandlerList[i].selected=!1;e.selected=!0,this.clickHandlerList=[e],EventBus.$emit("message--open",{message:this.clickHandlerList[0]})}EventBus.$emit("message--list",{list:this.clickHandlerList})},sortBy:function(e){if(!e)return!1;this.sortKey=e;var t=parseInt(this.sortOrders[this.sortKey])||1;this.sortKey in this.sortOrders?this.$set(this.sortOrders,this.sortKey,-1*t):(this.sortOrders={},this.$set(this.sortOrders,this.sortKey,1))}}}),b=g,k=(s("9701"),Object(u["a"])(b,h,v,!1,null,"3b53fd80",null)),C=k.exports,w={name:"Messages",components:{GridTemplate:C},props:{messages:Array,folders:Array,foldersFilterMove:Array,moveFolders:Array,messageMoveSelected:String},data:function(){return{searchQuery:"",gridColumns:["hasAttachment","priority","isRead","subject","senderConnect","timeFormat"],gridColumnsHeader:["","","","Betreff","Sender","Datum"]}},created:function(){},methods:{messageMove:function(){EventBus.$emit("message--move",{toFolder:this.messageMoveSelected})},messageDelete:function(){EventBus.$emit("message--delete",{})}}},y=w,x=Object(u["a"])(y,f,m,!1,null,"062952b8",null),_=x.exports,A=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"message flex flex-10"},[e.message.id?s("div",{staticClass:"head"},[s("div",[s("label",[e._v("Von")]),e._v(e._s(e.message.senderConnect))]),s("div",[s("label",[e._v("Datum")]),e._v(e._s(e.message.timeFormat))]),s("div",[s("label",[e._v("Betreff")]),"HIGH"==e.message.priority?s("span",{staticClass:"text-red"},[s("i",{staticClass:"fa fa-arrow-up"}),e._v("\n        Wichtig:\n      ")]):"LOW"==e.message.priority?s("i",{staticClass:"fa fa-arrow-down text-green"}):e._e(),e._v("\n      "+e._s(e.message.subject)+"\n    "),s("div",[e.message.hasAttachment?s("div",[s("label",[e._v("Anhang")]),s("i",{staticClass:"fa fa-file-o"}),e._v("\n      "+e._s(e.message.hasAttachment)+"\n    ")]):e._e()]),e.message.id?s("div",{staticClass:"body",domProps:{innerHTML:e._s(e.message.text)}}):e._e()])]):e._e()])},R=[],H={name:"Message",props:{message:Object}},O=H,S=Object(u["a"])(O,A,R,!1,null,"1e801ffc",null),F=S.exports,L=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"bar flex-2"},[e._v("\n  Bar:\n  "),s("ul",[s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Antworten\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Allen Antworten\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Weiterleiten\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Verschieben\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Archivieren\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Drucken (PDF)\n      ")])]),s("li",[s("button",{staticClass:"btn-primary",on:{click:function(t){return e.clickHandler()}}},[e._v("\n        Löschen\n      ")])])])])},M=[],N={name:"Bar",props:{}},B=N,j=Object(u["a"])(B,L,M,!1,null,"65965b8e",null),E=j.exports,T=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"form flex-9"},[s("form",{ref:"form",attrs:{action:"index.php?page=MessageCompose&action=send",method:"post",enctype:"multipart/form-data",id:"composeForm"},on:{submit:e.checkForm}},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.messageRecipients,expression:"messageRecipients"}],attrs:{type:"hidden",name:"recipients"},domProps:{value:e.messageRecipients},on:{input:function(t){t.target.composing||(e.messageRecipients=t.target.value)}}}),s("input",{attrs:{type:"hidden",name:"ccrecipients",value:""}}),s("input",{attrs:{type:"hidden",name:"bccrecipients",value:""}}),s("input",{directives:[{name:"model",rawName:"v-model",value:e.messageSubject,expression:"messageSubject"}],attrs:{type:"hidden",name:"messageSubject"},domProps:{value:e.messageSubject},on:{input:function(t){t.target.composing||(e.messageSubject=t.target.value)}}}),s("input",{directives:[{name:"model",rawName:"v-model",value:e.priority,expression:"priority"}],attrs:{type:"hidden",name:"priority",value:""},domProps:{value:e.priority},on:{input:function(t){t.target.composing||(e.priority=t.target.value)}}}),s("input",{directives:[{name:"model",rawName:"v-model",value:e.messageAttachments,expression:"messageAttachments"}],attrs:{type:"hidden",name:"attachments"},domProps:{value:e.messageAttachments},on:{input:function(t){t.target.composing||(e.messageAttachments=t.target.value)}}}),s("input",{attrs:{type:"hidden",name:"questions",value:""}}),s("input",{directives:[{name:"model",rawName:"v-model",value:e.readConfirmation,expression:"readConfirmation"}],attrs:{type:"hidden",name:"readConfirmation",value:""},domProps:{value:e.readConfirmation},on:{input:function(t){t.target.composing||(e.readConfirmation=t.target.value)}}}),s("input",{attrs:{type:"hidden",name:"forwardMessage",value:""}}),s("input",{attrs:{type:"hidden",name:"replyMessage",value:""}}),s("input",{attrs:{type:"hidden",name:"replyAllMessage",value:""}}),s("input",{directives:[{name:"model",rawName:"v-model",value:e.dontAllowAnser,expression:"dontAllowAnser"}],attrs:{type:"hidden",name:"dontAllowAnser",value:""},domProps:{value:e.dontAllowAnser},on:{input:function(t){t.target.composing||(e.dontAllowAnser=t.target.value)}}}),s("textarea",{directives:[{name:"model",rawName:"v-model",value:e.messageText,expression:"messageText"}],staticClass:"hidden",attrs:{name:"messageText"},domProps:{value:e.messageText},on:{input:function(t){t.target.composing||(e.messageText=t.target.value)}}})]),s("div",{staticClass:"bar margin-b-l"},[s("button",{staticClass:"btn btn-grau margin-r-xs",on:{click:function(t){return e.clickHandlerCloseForm()}}},[s("i",{staticClass:"fa fa-arrow-left"})]),s("button",{staticClass:"btn btn-grau margin-r-xs",on:{click:function(t){return e.clickHandlerClearForm()}}},[e._v("Abbrechen")]),s("button",{staticClass:"btn btn-blau margin-r-xs",on:{click:function(t){return e.clickHandlerSubmitForm()}}},[s("i",{staticClass:"fa fa-paper-plane"}),e._v("Senden")])]),s("ul",{staticClass:"margin-b-m"},[s("li",{staticClass:"flex-row margin-b-s"},[s("label",{staticClass:"flex-1"},[e._v("Empfänger:")]),s("div",{staticClass:"flex-6 flex-row"},[s("FormRecipient",{directives:[{name:"show",rawName:"v-show",value:e.openRecipients,expression:"openRecipients"}],attrs:{type:"recipients"}}),s("button",{directives:[{name:"show",rawName:"v-show",value:!e.openRecipients,expression:"!openRecipients"}],staticClass:"btn btn-grau margin-r-s",on:{click:function(t){return e.clickHandlerRecipients()}}},[s("i",{staticClass:"fa fa-plus"})]),s("ul",{directives:[{name:"show",rawName:"v-show",value:!e.openRecipients,expression:"!openRecipients"}]},e._l(e.messageRecipientsArray,(function(t,i){return s("li",{key:i},[e._v(e._s(t.name))])})),0)],1)]),s("li",{staticClass:"flex-row margin-b-s"},[s("label",{staticClass:"flex-1"},[e._v("Kopieempfänger cc:")]),s("div",{staticClass:"flex-6 flex-row"},[s("FormRecipient",{directives:[{name:"show",rawName:"v-show",value:e.openCcRecipients,expression:"openCcRecipients"}],attrs:{type:"ccrecipients"}}),s("button",{directives:[{name:"show",rawName:"v-show",value:!e.openCcRecipients,expression:"!openCcRecipients"}],staticClass:"btn btn-grau margin-r-s",on:{click:function(t){return e.clickHandlerCcRecipients()}}},[s("i",{staticClass:"fa fa-plus"})]),s("ul",{directives:[{name:"show",rawName:"v-show",value:!e.openCcRecipients,expression:"!openCcRecipients"}]},e._l(e.messageCcRecipientsArray,(function(t,i){return s("li",{key:i},[e._v(e._s(t.name))])})),0)],1)]),s("li",{staticClass:"flex-row margin-b-s"},[s("label",{staticClass:"flex-1"},[e._v("Verdeckte Kopieempfänger bcc:")]),s("div",{staticClass:"flex-6 flex-row"},[s("FormRecipient",{directives:[{name:"show",rawName:"v-show",value:e.openBccRecipients,expression:"openBccRecipients"}],attrs:{type:"bccrecipients"}}),s("button",{directives:[{name:"show",rawName:"v-show",value:!e.openBccRecipients,expression:"!openBccRecipients"}],staticClass:"btn btn-grau margin-r-s",on:{click:function(t){return e.clickHandlerBccRecipients()}}},[s("i",{staticClass:"fa fa-plus"})]),s("ul",{directives:[{name:"show",rawName:"v-show",value:!e.openBccRecipients,expression:"!openBccRecipients"}]},e._l(e.messageBccRecipientsArray,(function(t,i){return s("li",{key:i},[e._v(e._s(t.name))])})),0)],1)])]),s("ul",[s("li",{staticClass:"flex-row margin-b-m"},[s("label",{staticClass:"flex-1"},[e._v("Betreff:")]),s("input",{directives:[{name:"model",rawName:"v-model",value:e.messageSubject,expression:"messageSubject"}],staticClass:"flex-6",attrs:{type:"text"},domProps:{value:e.messageSubject},on:{input:function(t){t.target.composing||(e.messageSubject=t.target.value)}}})]),s("li",{staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Nachricht:")]),s("textarea",{directives:[{name:"model",rawName:"v-model",value:e.messageText,expression:"messageText"}],staticClass:"flex-6",domProps:{value:e.messageText},on:{input:function(t){t.target.composing||(e.messageText=t.target.value)}}})])]),s("ul",[s("li",{staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Dateianhänge")]),s("div",{staticClass:"flex-6"},[s("ul",e._l(e.filesAttachment,(function(t,i){return s("li",{key:i},[s("a",{attrs:{href:t.attachmentURL,target:"_blank"}},[e._v(e._s(t.attachmentFileName))]),s("button",{on:{click:function(s){return e.deleteFileUpload(t)}}},[e._v("Delete")])])})),0),s("input",{ref:"files",attrs:{type:"file",name:"attachmentFile"},on:{change:function(t){return e.handleFileUpload()}}}),s("button",{on:{click:function(t){return e.submitFileUpload()}}},[e._v("Datenanhang hochladen")]),s("p",{staticClass:"help-block"},[e._v("Maximal 10 MB pro Datei. (Office Dokumente, PDF Dateien, ZIP Dateien und Bilder)")])])]),s("li",{directives:[{name:"show",rawName:"v-show",value:e.acl.canAskQuestions,expression:"acl.canAskQuestions"}],staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Datenabfragen")]),s("div",{staticClass:"flex-6"})]),s("li",{directives:[{name:"show",rawName:"v-show",value:e.acl.canRequestReadConfirmation,expression:"acl.canRequestReadConfirmation"}],staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Lesebestätigung anfordern")]),s("div",{staticClass:"flex-6"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.readConfirmation,expression:"readConfirmation"}],attrs:{type:"checkbox",value:"1"},domProps:{checked:Array.isArray(e.readConfirmation)?e._i(e.readConfirmation,"1")>-1:e.readConfirmation},on:{change:function(t){var s=e.readConfirmation,i=t.target,n=!!i.checked;if(Array.isArray(s)){var r="1",a=e._i(s,r);i.checked?a<0&&(e.readConfirmation=s.concat([r])):a>-1&&(e.readConfirmation=s.slice(0,a).concat(s.slice(a+1)))}else e.readConfirmation=n}}})])]),s("li",{staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Antworten nicht erlauben?")]),s("div",{staticClass:"flex-6"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.dontAllowAnser,expression:"dontAllowAnser"}],attrs:{type:"checkbox",value:"1"},domProps:{checked:Array.isArray(e.dontAllowAnser)?e._i(e.dontAllowAnser,"1")>-1:e.dontAllowAnser},on:{change:function(t){var s=e.dontAllowAnser,i=t.target,n=!!i.checked;if(Array.isArray(s)){var r="1",a=e._i(s,r);i.checked?a<0&&(e.dontAllowAnser=s.concat([r])):a>-1&&(e.dontAllowAnser=s.slice(0,a).concat(s.slice(a+1)))}else e.dontAllowAnser=n}}})])]),s("li",{staticClass:"flex-row"},[s("label",{staticClass:"flex-1"},[e._v("Priorität")]),s("div",{staticClass:"flex-6"},[s("select",{directives:[{name:"model",rawName:"v-model",value:e.priority,expression:"priority"}],on:{change:function(t){var s=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){var t="_value"in e?e._value:e.value;return t}));e.priority=t.target.multiple?s:s[0]}}},[s("option",{attrs:{value:"low"}},[e._v("Niedrige Priorität")]),s("option",{attrs:{value:"normal",selected:""}},[e._v("Normale Priorität")]),s("option",{attrs:{value:"high"}},[e._v("Hohe Priorität")])])])])])])},P=[],D=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"flex-1 bg-white form-recipients flex-row height_35 border-radius"},[s("div",{staticClass:"flex-1 flex border-r scroll"},[s("button",{staticClass:"btn chevron",class:e.isTabActive("tab-1"),on:{click:function(t){return e.clickHandlerRecipientsTabOpen("tab-1")}}},[e._v("Allgemein")]),s("button",{staticClass:"btn chevron",class:e.isTabActive("tab-2"),on:{click:function(t){return e.clickHandlerRecipientsTabOpen("tab-2")}}},[e._v("Lehrer")]),s("button",{staticClass:"btn chevron",class:e.isTabActive("tab-3"),on:{click:function(t){return e.clickHandlerRecipientsTabOpen("tab-3")}}},[e._v("Schüler")]),s("button",{staticClass:"btn chevron",class:e.isTabActive("tab-4"),on:{click:function(t){return e.clickHandlerRecipientsTabOpen("tab-4")}}},[e._v("Eltern")]),s("button",{staticClass:"btn chevron",class:e.isTabActive("tab-5"),on:{click:function(t){return e.clickHandlerRecipientsTabOpen("tab-5")}}},[e._v("Sonstige")])]),s("div",{staticClass:"flex-1 border-r"},[s("div",{directives:[{name:"show",rawName:"v-show",value:"tab-1"==e.recipientsTabOpen,expression:"recipientsTabOpen == 'tab-1'"}],staticClass:"tab flex scroll"},[s("button",{staticClass:"btn",class:e.isRecipientSelected({key:"SL"}),on:{click:function(t){return e.clickHandlerRecipientsSelect({key:"SL",name:"Schulleitung"})}}},[e._v("Schulleitung")]),s("button",{staticClass:"btn",class:e.isRecipientSelected({key:"PR"}),on:{click:function(t){return e.clickHandlerRecipientsSelect({key:"PR",name:"Personalrat"})}}},[e._v("Personalrat")]),s("button",{staticClass:"btn",class:e.isRecipientSelected({key:"VW"}),on:{click:function(t){return e.clickHandlerRecipientsSelect({key:"VW",name:"Verwaltung"})}}},[e._v("Verwaltung")]),s("button",{staticClass:"btn",class:e.isRecipientSelected({key:"HM"}),on:{click:function(t){return e.clickHandlerRecipientsSelect({key:"HM",name:"Hausmeister"})}}},[e._v("Hausmeister")])]),s("div",{directives:[{name:"show",rawName:"v-show",value:"tab-2"==e.recipientsTabOpen,expression:"recipientsTabOpen == 'tab-2'"}],staticClass:"tab flex scroll"},[s("button",{staticClass:"btn",class:e.isRecipientSelected({key:"all_teacher"}),on:{click:function(t){return e.clickHandlerRecipientsSelect({key:"all_teacher",name:"Alle Lehrer"})}}},[e._v("Alle Lehrer")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-1"),on:{click:function(t){return e.clickHandlerRecipientsLehrer("list-1")}}},[e._v("Alle")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-2"),on:{click:function(t){return e.clickHandlerRecipientsFachschaft("list-2")}}},[e._v("Fachschaft")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-3"),on:{click:function(t){return e.clickHandlerKlassenteam("list-3")}}},[e._v("Klassenlehrer")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-4"),on:{click:function(t){return e.clickHandlerKlassenleitung("list-4")}}},[e._v("Klassenleitungen")])]),s("div",{directives:[{name:"show",rawName:"v-show",value:"tab-3"==e.recipientsTabOpen,expression:"recipientsTabOpen == 'tab-3'"}],staticClass:"tab flex scroll"},[s("button",{staticClass:"btn chevron",class:e.isListActive("list-1"),on:{click:function(t){return e.clickHandlerRecipientsSchueler("list-1")}}},[e._v("Alle")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-2"),on:{click:function(t){return e.clickHandlerRecipientsSchuelerKlassen("list-2")}}},[e._v("Klassen")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-3"),on:{click:function(t){return e.clickHandlerRecipientsSchuelerOwnUnterricht("list-3")}}},[e._v("Des eigenen Unterrichts")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-4"),on:{click:function(t){return e.clickHandlerRecipientsSchuelerAllUnterricht("list-4")}}},[e._v("Aller Unterrichte")])]),s("div",{directives:[{name:"show",rawName:"v-show",value:"tab-4"==e.recipientsTabOpen,expression:"recipientsTabOpen == 'tab-4'"}],staticClass:"tab flex scroll"},[s("button",{staticClass:"btn chevron",class:e.isListActive("list-1"),on:{click:function(t){return e.clickHandlerRecipientsElternKlassen("list-1")}}},[e._v("Eltern der ganzen Klasse")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-2"),on:{click:function(t){return e.clickHandlerRecipientsElternSingel("list-2")}}},[e._v("Einzelne Eltern")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-3"),on:{click:function(t){return e.clickHandlerRecipientsOwnUnterricht("list-3")}}},[e._v("Eltern der Schüler des eigenen Unterrichts")]),s("button",{staticClass:"btn chevron",class:e.isListActive("list-4"),on:{click:function(t){return e.clickHandlerRecipientsAllUnterricht("list-4")}}},[e._v("Eltern der Schüler aller Unterrichte")])]),s("div",{directives:[{name:"show",rawName:"v-show",value:"tab-5"==e.recipientsTabOpen,expression:"recipientsTabOpen == 'tab-5'"}],staticClass:"tab flex scroll"},[e._v("\n      gruppe,,,,\n    ")])]),s("div",{staticClass:"flex-1 flex border-r margin-r-xs height_35 "},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.recipientsResultSearched,expression:"recipientsResultSearched"}]},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.searchQuery,expression:"searchQuery"}],attrs:{type:"search",placeholder:"Suche..."},domProps:{value:e.searchQuery},on:{input:function(t){t.target.composing||(e.searchQuery=t.target.value)}}}),s("button",{on:{click:function(t){return e.clickHandlerSearchClear()}}},[e._v("Clear")])]),s("div",{staticClass:"scroll"},e._l(e.recipientsResultSearched,(function(t,i){return s("button",{key:i,staticClass:"btn",class:e.isRecipientSelected(t),on:{click:function(s){return e.clickHandlerRecipientsSelect(t)}}},[e._v(e._s(t.text))])})),0)]),s("div",{staticClass:"flex-1 flex border-l scroll"},[e._l(e.recipientsSelect,(function(t,i){return s("button",{key:i,staticClass:"btn",class:e.isRecipientSelected(t),on:{click:function(s){return e.clickHandlerRecipientsSelect(t)}}},[e._v(e._s(t.name))])})),s("button",{staticClass:"btn btn-blau",on:{click:function(t){return e.clickCloseForm()}}},[e._v("OK")])],2)])},K=[],$=(s("7f7f"),s("bc3a").default),U={name:"FormRecipient",components:{},props:{type:String},data:function(){return{recipientsTabOpen:"tab-1",recipientsSelect:[],recipientsResult:[],recipientsListOpen:"",searchQuery:""}},computed:{recipientsResultSearched:function(){if(this.searchQuery&&this.recipientsResult){var e=this.recipientsResult,t=this.searchQuery;return e=e.filter((function(e){return Object.keys(e).some((function(s){return String(e[s]).toLowerCase().indexOf(t)>-1}))})),e}return this.recipientsResult}},created:function(){},methods:{clickHandlerSearchClear:function(){this.searchQuery=""},clickHandlerRecipientsTabOpen:function(e){this.recipientsTabOpen=e,this.recipientsListOpen=!1,this.recipientsResult=!1},clickHandlerRecipientsLehrer:function(e){var t=this;$.get("index.php?page=MessageCompose&action=getTeachersJSON&_type=query",{params:{}}).then((function(s){s.data.results&&(t.recipientsResult=s.data.results,t.recipientsListOpen=e)})).catch((function(e){console.log(e)}))},clickHandlerRecipientsFachschaft:function(e){this.recipientsResult=globals.selectOptionsFachschaften,this.recipientsListOpen=e},clickHandlerKlassenteam:function(e){this.recipientsResult=globals.selectOptionsKlassenteams,this.recipientsListOpen=e},clickHandlerKlassenleitung:function(e){this.recipientsResult=globals.selectOptionsKlassenleitung,this.recipientsListOpen=e},clickHandlerRecipientsSchueler:function(e){this.recipientsResult=globals.selectOptionsSchueler,this.recipientsListOpen=e},clickHandlerRecipientsSchuelerOwnUnterricht:function(e){this.recipientsResult=globals.selectOptionsSchuelerOwnUnterricht,this.recipientsListOpen=e},clickHandlerRecipientsSchuelerAllUnterricht:function(e){this.recipientsResult=globals.selectOptionsSchuelerAllUnterricht,this.recipientsListOpen=e},clickHandlerRecipientsSchuelerKlassen:function(e){this.recipientsResult=globals.selectOptionsSchuelerKlassen,this.recipientsListOpen=e},clickHandlerRecipientsElternSingel:function(e){this.recipientsResult=globals.selectOptionsElternSingel,this.recipientsListOpen=e},clickHandlerRecipientsOwnUnterricht:function(e){this.recipientsResult=globals.selectOptionsElternOwnUnterricht,this.recipientsListOpen=e},clickHandlerRecipientsAllUnterricht:function(e){this.recipientsResult=globals.selectOptionsElternAllUnterricht,this.recipientsListOpen=e},clickHandlerRecipientsElternKlassen:function(e){this.recipientsListOpen=e},clickHandlerRecipientsSelect:function(e){if(!e.id&&!e.key)return!1;e.key&&(e.id=e.key,e.text=e.name);for(var t=!1,s=0,i=this.recipientsSelect.length;s<i;s++)this.recipientsSelect[s].key==e.id&&(t=this.recipientsSelect[s]);if(t){var n=this.recipientsSelect.indexOf(t);n>-1&&this.recipientsSelect.splice(n,1)}else this.recipientsSelect.push({key:e.id,name:e.text})},clickCloseForm:function(){for(var e="",t=0;t<this.recipientsSelect.length;t++)t>0&&(e+=";"),e+=this.recipientsSelect[t]["key"];EventBus.$emit("message--form--set-recipient",{type:this.type,recipientsString:e,recipientsArray:this.recipientsSelect})},isRecipientSelected:function(e){for(var t=0,s=this.recipientsSelect.length;t<s;t++)if(this.recipientsSelect[t].key==e.id||this.recipientsSelect[t].key==e.key)return"selected";return!1},isTabActive:function(e){return this.recipientsTabOpen==e&&"active"},isListActive:function(e){return this.recipientsListOpen==e&&"active"}}},I=U,G=Object(u["a"])(I,D,K,!1,null,"2b1bd805",null),Q=G.exports,V=s("bc3a").default,W={name:"Form",components:{FormRecipient:Q},props:{},data:function(){return{messageText:"",messageSubject:"",messageAttachments:"",dontAllowAnser:!1,readConfirmation:!1,openRecipients:!1,messageRecipientsArray:[],messageRecipients:"",openCcRecipients:!1,messageCcRecipientsArray:[],messageCcRecipients:"",openBccRecipients:!1,messageBccRecipientsArray:[],messageBccRecipients:"",acl:{},filesUpload:[],filesAttachment:[]}},computed:{},created:function(){var e=this;this.acl=globals.acl,EventBus.$on("message--form--set-recipient",(function(t){"recipients"==t.type?(e.messageRecipients=t.recipientsString,e.messageRecipientsArray=t.recipientsArray,e.openRecipients=!1):"ccrecipients"==t.type?(e.messageCcRecipients=t.recipientsString,e.messageCcRecipientsArray=t.recipientsArray,e.openCcRecipients=!1):"bccrecipients"==t.type&&(e.messageBccRecipients=t.recipientsString,e.messageBccRecipientsArray=t.recipientsArray,e.openBccRecipients=!1)}))},methods:{checkForm:function(e){e.preventDefault()},clickHandlerSubmitForm:function(){this.$refs.form.submit()},clickHandlerCloseForm:function(){EventBus.$emit("message--form--close",{})},clickHandlerClearForm:function(){this.messageText="",this.messageSubject="",this.messageAttachments="",this.dontAllowAnser=0,this.readConfirmation=0,this.openRecipients=!1,this.messageRecipientsArray=[],this.messageRecipients="",this.openCcRecipients=!1,this.messageCcRecipientsArray=[],this.messageCcRecipients="",this.openBccRecipients=!1,this.messageBccRecipientsArray=[],this.messageBccRecipients="",this.clickHandlerCloseForm()},clickHandlerRecipients:function(){this.openRecipients=!0},clickHandlerCcRecipients:function(){this.openCcRecipients=!0},clickHandlerBccRecipients:function(){this.openBccRecipients=!0},handleFileUpload:function(){this.filesUpload=this.$refs.files.files[0]},clearFileUpload:function(){this.$refs.files.value="",this.filesUpload=""},deleteFileUpload:function(e){for(var t=0;t<this.filesAttachment.length;t++)this.filesAttachment[t]["attachmentID"]==e["attachmentID"]&&this.filesAttachment.splice(t,1);this.clearFileUpload()},submitFileUpload:function(){var e=this,t=new FormData;t.append("attachmentFile",this.filesUpload),V.post("index.php?page=MessageCompose&action=uploadAttachment",t,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){t.data&&(1==t.data.uploadOK?(e.filesAttachment.push(t.data),e.updateAttachmentFields()):console.error("fehler"))})).catch((function(){console.log("FAILURE!!")})).finally((function(){e.clearFileUpload()}))},updateAttachmentFields:function(){for(var e=[],t=0;t<this.filesAttachment.length;t++)e.push(this.filesAttachment[t]["attachmentID"]+"#"+this.filesAttachment[t]["attachmentAccessCode"]);this.messageAttachments=e.join(";")}}},q=W,J=Object(u["a"])(q,T,P,!1,null,"395c5c67",null),z=J.exports,Z=s("bc3a").default;Z.defaults.headers.common["x-authorization"]="112233";var X={name:"app",components:{Folders:d,Messages:_,Message:F,Bar:E,Form:z},data:function(){return{folders:{},foldersFilterMove:[],messages:[],message:{},errorMsg:!1,show:{list:!0,preview:!1,form:!1},activeFolder:!1,handlerClickList:[]}},created:function(){var e=this,t=this;EventBus.$on("message--move",(function(s){if(!s.toFolder.folderName&&!s.toFolder.folderID)return!1;var i="rest.php/MoveMsgMessage/"+globals.userID+"/"+encodeURIComponent(s.toFolder.folderName)+"/"+s.toFolder.folderID;t.ajaxPost(i,{list:JSON.stringify(e.handlerClickList)},{},(function(e,t){if(1==e.data.done){for(var s=0;s<t.messages.length;s++)for(var i=0;i<t.handlerClickList.length;i++)if(t.messages[s].id==t.handlerClickList[i].id){var n=t.messages.indexOf(t.handlerClickList[i]);t.messages.splice(n,1)}}else t.errorMsg="Beim Verschieben ist leider ein Fehler aufgetreten. (Code:Ajax Move Message 404)"}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Move Message 404)"}))})),EventBus.$on("message--delete",(function(s){var i="rest.php/DeleteMsgMessage/"+globals.userID+"/"+e.activeFolder.folderName+"/"+e.activeFolder.folderID;t.ajaxPost(i,{list:JSON.stringify(e.handlerClickList)},{},(function(e,t){if(1==e.data.done){for(var s=0;s<t.messages.length;s++)for(var i=0;i<t.handlerClickList.length;i++)if(t.messages[s].id==t.handlerClickList[i].id){var n=t.messages.indexOf(t.handlerClickList[i]);t.messages.splice(n,1)}}else t.errorMsg="Beim Löschen ist leider ein Fehler aufgetreten. (Code:Ajax Delete Message 404)"}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Delete Message 404)"}))})),EventBus.$on("message--list",(function(t){e.handlerClickList=t.list})),EventBus.$on("message--open",(function(e){var s="rest.php/GetMsgMessage/"+globals.userID;e.message.id&&(s+="/"+parseInt(e.message.id)),t.ajaxGet(s,{},(function(e,t){t.message=e.data,t.show.list=!0,t.show.preview=!0,t.showform=!1}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Message 404)"}))})),EventBus.$on("message--close",(function(e){t.message=!1,t.show.list=!0,t.show.preview=!1,t.showform=!1})),EventBus.$on("folders--get",(function(e){t.ajaxGet("rest.php/GetMsgFolders/"+globals.userID,{},(function(e,t){for(var s in t.folders=e.data,t.folders)"Gesendete"!=t.folders[s].folderName&&t.foldersFilterMove.push(t.folders[s])}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Folders 404)"}))})),EventBus.$on("messages--changeFolder",(function(s){var i="rest.php/GetMsgMessages/"+globals.userID;s.folder.isStandardFolder&&!s.folder.folderID?i+="/"+s.folder.folderName:i+="/"+s.folder.folderID,e.activeFolder=s.folder,t.ajaxGet(i,{},(function(e,t){e.data?(t.messages=e.data,t.message={},t.show.list=!0,t.show.preview=!1,t.showform=!1):t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Data)"}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Change Folders 404)"}))})),EventBus.$on("message--form",(function(e){var s="rest.php/GetMsgForm/"+globals.userID;t.ajaxGet(s,{},(function(e,t){e.data?(t.show.list=!1,t.show.preview=!1,t.show.form=!0,t.message={}):t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Form Data)"}),(function(e){t.errorMsg="Es ist leider ein Fehler aufgetreten. (Code:Ajax Open Form 404)"}))})),EventBus.$on("message--form--close",(function(t){console.log("close form"),e.show.list=!0,e.show.preview=!1,e.show.form=!1})),EventBus.$emit("messages--changeFolder",{folder:{isStandardFolder:!0,folderName:"Posteingang",folderID:0}})},methods:{ajaxGet:function(e,t,s,i,n){var r=this;Z.get(e,{params:t}).then((function(e){s&&"function"===typeof s&&s(e,r)})).catch((function(e){e&&"function"===typeof i&&i(e)})).finally((function(){n&&"function"===typeof n&&n()}))},ajaxPost:function(e,t,s,i,n,r){var a=this;Z.post(e,t,{params:s}).then((function(e){i&&"function"===typeof i&&i(e,a)})).catch((function(e){e&&"function"===typeof n&&n(e)})).finally((function(){r&&"function"===typeof r&&r()}))}}},Y=X,ee=Object(u["a"])(Y,n,r,!1,null,null,null),te=ee.exports;window.EventBus=new i["a"],i["a"].config.productionTip=!1,new i["a"]({render:function(e){return e(te)}}).$mount("#app")},9701:function(e,t,s){"use strict";var i=s("fae9"),n=s.n(i);n.a},fae9:function(e,t,s){}});
//# sourceMappingURL=app.js.map