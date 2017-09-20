/* global wp, bp, BP_Progenitor, _, Backbone, tinymce, tinyMCE */
/* jshint devel: true */
window.wp = window.wp || {};
window.bp = window.bp || {};

( function( exports, $ ) {

	// Bail if not set
	if ( typeof BP_Progenitor === 'undefined' ) {
		return;
	}

	_.extend( bp, _.pick( wp, 'Backbone', 'ajax', 'template' ) );

	bp.Models      = bp.Models || {};
	bp.Collections = bp.Collections || {};
	bp.Views       = bp.Views || {};

	bp.Progenitor = bp.Progenitor || {};

	/**
	 * [Progenitor description]
	 * @type {Object}
	 */
	bp.Progenitor.Messages = {
		/**
		 * [start description]
		 * @return {[type]} [description]
		 */
		start: function() {
			this.views    = new Backbone.Collection();
			this.threads  = new bp.Collections.Threads();
			this.messages = new bp.Collections.Messages();
			this.router   = new bp.Progenitor.Messages.Router();
			this.box      = 'inbox';

			this.setupNav();

			Backbone.history.start();
		},

		setupNav: function() {
			var self = this;

			// First adapt the compose nav
			$( '#compose-personal-li' ).addClass( 'last' );
			$( '#compose-personal-li a#compose').html(
				$( '<span></span>' ).html( $( '#compose-personal-li a#compose' ).html() ).addClass( 'bp-screen-reader-text' )
			);
			$( '#compose-personal-li a#compose' ).addClass( 'button' );

			// Then listen to nav click and load the appropriate view
			$( '#subnav a' ).on( 'click', function( event ) {
				event.preventDefault();

				var view_id = $( event.target ).prop( 'id' );

				// Remove the editor to be sure it will be added dynamically later
				self.removeTinyMCE();

				// The compose view is specific (toggle behavior)
				if ( 'compose' === view_id ) {
					// If it exists, it means the user wants to remove it
					if ( ! _.isUndefined( self.views.get( 'compose' ) ) ) {
						var form = self.views.get( 'compose' );
						form.get( 'view' ).remove();
						self.views.remove( { id: 'compose', view: form } );

						// Back to inbox
						if ( 'single' === self.box ) {
							self.box = 'inbox';
						}

						// Navigate back to current box
						self.router.navigate( self.box, { trigger: true } );

					// Otherwise load it
					} else {
						self.router.navigate( 'compose', { trigger: true } );
					}

				// Other views are classic.
				} else {

					if ( self.box !== view_id || ! _.isUndefined( self.views.get( 'compose' ) ) ) {
						self.clearViews();

						self.router.navigate( view_id, { trigger: true } );
					}
				}
			} );
		},

		removeTinyMCE: function() {
			if ( typeof tinymce !== 'undefined' ) {
				var editor = tinymce.get( 'message_content' );

				if ( editor !== null ) {
					tinymce.EditorManager.execCommand( 'mceRemoveEditor', true, 'message_content' );
				}
			}
		},

		tinyMCEinit: function() {
			if ( typeof window.tinyMCE === 'undefined' || window.tinyMCE.activeEditor === null || typeof window.tinyMCE.activeEditor === 'undefined' ) {
				return;
			} else {
				$( window.tinyMCE.activeEditor.contentDocument.activeElement )
					.atwho( 'setIframe', $( '#message_content_ifr' )[0] )
					.bp_mentions( {
						data: [],
						suffix: ' '
					} );
			}
		},

		removeFeedback: function() {
			var feedback;

			if ( ! _.isUndefined( this.views.get( 'feedback' ) ) ) {
				feedback = this.views.get( 'feedback' );
				feedback.get( 'view' ).remove();
				this.views.remove( { id: 'feedback', view: feedback } );
			}
		},

		displayFeedback: function( message, type ) {
			var feedback;

			// Make sure to remove the feedbacks
			this.removeFeedback();

			if ( ! message ) {
				return;
			}

			feedback = new bp.Views.Feedback( {
				value: message,
				type:  type || 'info'
			} );

			this.views.add( { id: 'feedback', view: feedback } );

			feedback.inject( '.bp-messages-feedback' );
		},

		clearViews: function() {
			// Clear views
			if ( ! _.isUndefined( this.views.models ) ) {
				_.each( this.views.models, function( model ) {
					model.get( 'view' ).remove();
				}, this );

				this.views.reset();
			}
		},

		composeView: function() {
			// Remove all existing views.
			this.clearViews();

			// Create the loop view
			var form = new bp.Views.messageForm( {
				model: new bp.Models.Message()
			} );

			this.views.add( { id: 'compose', view: form } );

			form.inject( '.bp-messages-content' );
		},

		threadsView: function() {
			// Activate the appropriate nav
			$( '#subnav ul li' ).each( function( l, li ) {
				$( li ).removeClass( 'current selected' );
			} );
			$( '#subnav a#' + this.box ).closest( 'li' ).addClass( 'current selected' );

			// Create the loop view
			var threads_list = new bp.Views.userThreads( { collection: this.threads, box: this.box } );

			this.views.add( { id: 'threads', view: threads_list } );

			threads_list.inject( '.bp-messages-content' );

			// Attach filters
			this.displayFilters( this.threads );
		},

		displayFilters: function( collection ) {
			var filters_view;

			// Create the model
			this.filters = new Backbone.Model( {
				'page'         : 1,
				'total_page'   : 0,
				'search_terms' : '',
				'box'          : this.box
			} );

			// Use it in the filters viex
			filters_view = new bp.Views.messageFilters( { model: this.filters, threads: collection } );

			this.views.add( { id: 'filters', view: filters_view } );

			filters_view.inject( '.bp-messages-filters' );
		},

		singleView: function( thread ) {
			// Remove all existing views.
			this.clearViews();

			this.box = 'single';

			// Create the single thread view
			var single_thread = new bp.Views.userMessages( { collection: this.messages, thread: thread } );

			this.views.add( { id: 'single', view: single_thread } );

			single_thread.inject( '.bp-messages-content' );
		}
	};

	bp.Models.Message = Backbone.Model.extend( {
		defaults: {
			send_to         : [],
			subject         : '',
			message_content : '',
			meta            : {}
		},

		sendMessage: function() {
			if ( true === this.get( 'sending' ) ) {
				return;
			}

			this.set( 'sending', true, { silent: true } );

			var sent = bp.ajax.post( 'messages_send_message', _.extend(
				{
					nonce: BP_Progenitor.messages.nonces.send
				},
				this.attributes
			) );

			this.set( 'sending', false, { silent: true } );

			return sent;
		}
	} );

	bp.Models.Thread = Backbone.Model.extend( {
		defaults: {
			id            : 0,
			message_id    : 0,
			subject       : '',
			excerpt       : '',
			content       : '',
			unread        : true,
			sender_name   : '',
			sender_link   : '',
			sender_avatar : '',
			count         : 0,
			date          : 0,
			display_date  : '',
			recipients    : []
		},

		updateReadState: function( options ) {
			options = options || {};
			options.data = _.extend(
				_.pick( this.attributes, ['id', 'message_id'] ),
				{
					action : 'messages_thread_read',
					nonce  : BP_Progenitor.nonces.messages
				}
			);

			return bp.ajax.send( options );
		}
	} );

	bp.Models.messageThread = Backbone.Model.extend( {
		defaults: {
			id            : 0,
			content       : '',
			sender_id     : 0,
			sender_name   : '',
			sender_link   : '',
			sender_avatar : '',
			date          : 0,
			display_date  : ''
		}
	} );

	bp.Collections.Threads = Backbone.Collection.extend( {
		model: bp.Models.Thread,

		initialize : function() {
			this.options = { page: 1, total_page: 0 };
		},

		sync: function( method, model, options ) {
			options         = options || {};
			options.context = this;
			options.data    = options.data || {};

			// Add generic nonce
			options.data.nonce = BP_Progenitor.nonces.messages;

			if ( 'read' === method ) {
				options.data = _.extend( options.data, {
					action: 'messages_get_user_message_threads'
				} );

				return bp.ajax.send( options );
			}
		},

		parse: function( resp ) {

			if ( ! _.isArray( resp.threads ) ) {
				resp.threads = [resp.threads];
			}

			_.each( resp.threads, function( value, index ) {
				if ( _.isNull( value ) ) {
					return;
				}

				resp.threads[index].id            = value.id;
				resp.threads[index].message_id    = value.message_id;
				resp.threads[index].subject       = value.subject;
				resp.threads[index].excerpt       = value.excerpt;
				resp.threads[index].content       = value.content;
				resp.threads[index].unread        = value.unread;
				resp.threads[index].sender_name   = value.sender_name;
				resp.threads[index].sender_link   = value.sender_link;
				resp.threads[index].sender_avatar = value.sender_avatar;
				resp.threads[index].count         = value.count;
				resp.threads[index].date          = new Date( value.date );
				resp.threads[index].display_date  = value.display_date;
				resp.threads[index].recipients    = value.recipients;
				resp.threads[index].star_link     = value.star_link;
				resp.threads[index].is_starred    = value.is_starred;
			} );

			if ( ! _.isUndefined( resp.meta ) ) {
				this.options.page       = resp.meta.page;
				this.options.total_page = resp.meta.total_page;
			}

			if ( bp.Progenitor.Messages.box ) {
				this.options.box = bp.Progenitor.Messages.box;
			}

			return resp.threads;
		},

		doAction: function( action, ids, options ) {
			options         = options || {};
			options.context = this;
			options.data    = options.data || {};

			options.data = _.extend( options.data, {
				action: 'messages_' + action,
				nonce : BP_Progenitor.nonces.messages,
				id    : ids
			} );

			return bp.ajax.send( options );
		}
	} );

	bp.Collections.Messages = Backbone.Collection.extend( {
		model: bp.Models.messageThread,
		options: {},

		sync: function( method, model, options ) {
			options         = options || {};
			options.context = this;
			options.data    = options.data || {};

			// Add generic nonce
			options.data.nonce = BP_Progenitor.nonces.messages;

			if ( 'read' === method ) {
				options.data = _.extend( options.data, {
					action: 'messages_get_thread_messages'
				} );

				return bp.ajax.send( options );
			}

			if ( 'create' === method ) {
				options.data = _.extend( options.data, {
					action : 'messages_send_reply',
					nonce  : BP_Progenitor.messages.nonces.send
				}, model || {} );

				return bp.ajax.send( options );
			}

			/*if ( 'delete' === method ) {
				options.data = _.extend( options.data, {
					action     : 'groups_delete_group_invite',
					'_wpnonce' : BP_Progenitor.group_invites.nonces.uninvite
				} );

				if ( model ) {
					options.data.user = model;
				}

				return bp.ajax.send( options );
			}*/
		},

		parse: function( resp ) {

			if ( ! _.isArray( resp.messages ) ) {
				resp.messages = [resp.messages];
			}

			_.each( resp.messages, function( value, index ) {
				if ( _.isNull( value ) ) {
					return;
				}

				resp.messages[index].id            = value.id;
				resp.messages[index].content       = value.content;
				resp.messages[index].sender_id     = value.sender_id;
				resp.messages[index].sender_name   = value.sender_name;
				resp.messages[index].sender_link   = value.sender_link;
				resp.messages[index].sender_avatar = value.sender_avatar;
				resp.messages[index].date          = new Date( value.date );
				resp.messages[index].display_date  = value.display_date;
				resp.messages[index].star_link     = value.star_link;
				resp.messages[index].is_starred    = value.is_starred;
			} );

			if ( ! _.isUndefined( resp.thread ) ) {
				this.options.thread_id      = resp.thread.id;
				this.options.thread_subject = resp.thread.subject;
				this.options.recipients     = resp.thread.recipients;
			}

			return resp.messages;
		}
	} );

	// Extend wp.Backbone.View with .prepare() and .inject()
	bp.Progenitor.Messages.View = bp.Backbone.View.extend( {
		inject: function( selector ) {
			this.render();
			$(selector).html( this.el );
			this.views.ready();
		},

		prepare: function() {
			if ( ! _.isUndefined( this.model ) && _.isFunction( this.model.toJSON ) ) {
				return this.model.toJSON();
			} else {
				return {};
			}
		}
	} );

	// Feedback view
	bp.Views.Feedback = bp.Progenitor.Messages.View.extend( {
		tagName: 'div',
		className: 'bp-messages bp-user-messages-feedback',

		initialize: function() {
			this.value = this.options.value;

			if ( this.options.type ) {
				this.el.className += ' ' + this.options.type;
			}
		},

		render: function() {
			this.$el.html( this.value );
			return this;
		}
	} );

	bp.Views.messageEditor = bp.Progenitor.Messages.View.extend( {
		template  : bp.template( 'bp-messages-editor' ),

		initialize: function() {
			this.on( 'ready', this.activateTinyMce, this );
		},

		activateTinyMce: function() {
			if ( typeof tinymce !== 'undefined' ) {
				tinymce.EditorManager.execCommand( 'mceAddEditor', true, 'message_content' );
			}
		}
	} );

	bp.Views.messageForm = bp.Progenitor.Messages.View.extend( {
		tagName   : 'form',
		id        : 'send_message_form',
		className : 'standard-form',
		template  : bp.template( 'bp-messages-form' ),

		events: {
			'click #bp-messages-send'  : 'sendMessage',
			'click #bp-messages-reset' : 'resetForm'
		},

		initialize: function() {
			// Clone the model to set the resetted one
			this.resetModel = this.model.clone();

			// Add the editor view
			this.views.add( '#bp-message-content', new bp.Views.messageEditor() );

			this.model.on( 'change', this.resetFields, this );

			// Activate bp_mentions
			this.on( 'ready', this.addMentions, this );
		},

		addMentions: function() {
			// Add autocomplete to send_to field
			$( this.el ).find( '#send-to-input' ).bp_mentions( {
				data: [],
				suffix: ' '
			} );
		},

		resetFields: function( model ) {
			// Clean inputs
			_.each( model.previousAttributes(), function( value, input ) {
				if ( 'message_content' === input ) {
					// tinyMce
					if ( undefined !== tinyMCE.activeEditor && null !== tinyMCE.activeEditor ) {
						tinyMCE.activeEditor.setContent( '' );
					}

				// All except meta or empty value
				} else if ( 'meta' !== input && false !== value ) {
					$( 'input[name="' + input + '"]' ).val( '' );
				}
			} );

			// Listen to this to eventually reset your custom inputs.
			$( this.el ).trigger( 'message:reset', _.pick( model.previousAttributes(), 'meta' ) );
		},

		sendMessage: function( event ) {
			var meta = {}, errors = [], self = this;
			event.preventDefault();

			bp.Progenitor.Messages.removeFeedback();

			// Set the content and meta
			_.each( this.$el.serializeArray(), function( pair ) {
				pair.name = pair.name.replace( '[]', '' );

				// Group extra fields in meta
				if ( -1 === _.indexOf( ['send_to', 'subject', 'message_content'], pair.name ) ) {
					if ( _.isUndefined( meta[ pair.name ] ) ) {
						meta[ pair.name ] = pair.value;
					} else {
						if ( ! _.isArray( meta[ pair.name ] ) ) {
							meta[ pair.name ] = [ meta[ pair.name ] ];
						}

						meta[ pair.name ].push( pair.value );
					}

				// Prepare the core model
				} else {
					// Send to
					if ( 'send_to' === pair.name ) {
						var usernames = pair.value.match( /(^|[^@\w\-])@([a-zA-Z0-9_\-]{1,50})\b/g );

						if ( ! usernames ) {
							errors.push( 'send_to' );
						} else {
							usernames = usernames.map( function( username ) {
								username = $.trim( username );
								return username;
							} );

							if ( ! usernames || ! $.isArray( usernames ) ) {
								errors.push( 'send_to' );
							}

							this.model.set( 'send_to', usernames, { silent: true } );
						}

					// Subject and content
					} else {
						// Message content
						if ( 'message_content' === pair.name && undefined !== tinyMCE.activeEditor ) {
							pair.value = tinyMCE.activeEditor.getContent();
						}

						if ( ! pair.value ) {
							errors.push( pair.name );
						} else {
							this.model.set( pair.name, pair.value, { silent: true } );
						}
					}
				}

			}, this );

			if ( errors.length ) {
				var feedback = '';
				_.each( errors, function( e ) {
					feedback += '<div class="bp-feedback error"><span class="bp-icon" aria-hidden="true"></span><p>'+BP_Progenitor.messages.errors[ e ] + '</p></div>';
				} );

				bp.Progenitor.Messages.displayFeedback( feedback, 'error' );
				return;
			}

			// Set meta
			this.model.set( 'meta', meta, { silent: true } );

			// Send the message.
			this.model.sendMessage().done( function( response ) {
				// Reset the model
				self.model.set( self.resetModel );

				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );

				// Remove tinyMCE
				bp.Progenitor.Messages.removeTinyMCE();

				// Remove the form view
				var form = bp.Progenitor.Messages.views.get( 'compose' );
				form.get( 'view' ).remove();
				bp.Progenitor.Messages.views.remove( { id: 'compose', view: form } );

				bp.Progenitor.Messages.router.navigate( 'sentbox', { trigger: true } );
			} ).fail( function( response ) {
				if ( response.feedback ) {
					bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
				}
			} );
		},

		resetForm: function( event ) {
			event.preventDefault();

			this.model.set( this.resetModel );
		}
	} );

	bp.Views.userThreads = bp.Progenitor.Messages.View.extend( {
		tagName   : 'div',

		events: {
			'click .thread-content'       : 'changePreview',
			'dblclick .thread-content'    : 'loadSingleView'
		},

		initialize: function() {
			// Add the threads parent view
			this.views.add( new bp.Progenitor.Messages.View( { tagName: 'ul', id: 'message-threads', className: 'message-lists' } ) );

			// Add the preview Active Thread view
			this.views.add( new bp.Views.previewThread( { collection: this.collection } ) );

			// Load threads for the active view
			this.requestThreads();

			this.collection.on( 'reset', this.cleanContent, this );
			this.collection.on( 'add', this.addThread, this );
		},

		requestThreads: function() {
			this.collection.reset();

			bp.Progenitor.Messages.displayFeedback( BP_Progenitor.messages.loading, 'loading' );

			this.collection.fetch( {
				data    : _.pick( this.options, 'box' ),
				success : this.threadsFetched,
				error   : this.threadsFetchError
			} );
		},

		threadsFetched: function() {
			bp.Progenitor.Messages.removeFeedback();
		},

		threadsFetchError: function( collection, response ) {
			bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
		},

		cleanContent: function() {
			_.each( this.views._views['#message-threads'], function( view ) {
				view.remove();
			} );
		},

		addThread: function( thread ) {
			var selected = this.collection.findWhere( { active: true } );

			if ( _.isUndefined( selected ) ) {
				thread.set( 'active', true );
			}

			this.views.add( '#message-threads', new bp.Views.userThread( { model: thread } ) );
		},

		changePreview: function( event ) {
			var target = $( event.currentTarget );

			if ( ! target.hasClass( 'thread-content' ) || $( event.target ).hasClass( 'user-link' ) ) {
				return event;
			}

			event.preventDefault();

			if ( target.parent( 'li' ).hasClass( 'selected' ) ) {
				return;
			}

			var selected = target.data( 'thread-id' );

			_.each( this.collection.models, function( thread ) {
				if ( thread.id === selected ) {
					thread.set( 'active', true );
				} else {
					thread.unset( 'active' );
				}
			}, this );
		},

		loadSingleView: function( event ) {
			var target = $( event.currentTarget );

			if ( ! target.hasClass( 'thread-content' ) || $( event.target ).hasClass( 'user-link' ) ) {
				return event;
			}

			event.preventDefault();

			var id = target.data( 'thread-id' );

			bp.Progenitor.Messages.router.navigate( 'view/' + id, { trigger: true } );
		}
	} );

	bp.Views.userThread = bp.Progenitor.Messages.View.extend( {
		tagName   : 'li',
		template  : bp.template( 'bp-messages-thread' ),
		className : 'thread-item',

		events: {
			'click .message-check' : 'singleSelect'
		},

		initialize: function() {
			if ( this.model.get( 'active' ) ) {
				this.el.className += ' selected';
			}

			if ( this.model.get( 'unread' ) ) {
				this.el.className += ' unread';
			}

			this.model.on( 'change:active', this.toggleClass, this );
			this.model.on( 'change:unread', this.updateReadState, this );
			this.model.on( 'change:checked', this.bulkSelect, this );
			this.model.on( 'remove', this.cleanView, this );
		},

		toggleClass: function( model ) {
			if ( true === model.get( 'active' ) ) {
				$( this.el ).addClass( 'selected' );
			} else {
				$( this.el ).removeClass( 'selected' );
			}
		},

		updateReadState: function( model, state ) {
			if ( false === state ) {
				$( this.el ).removeClass( 'unread' );
			} else {
				$( this.el ).addClass( 'unread' );
			}
		},

		bulkSelect: function( model ) {
			if ( $( '#bp-message-thread-' + model.get( 'id' ) ).length ) {
				$( '#bp-message-thread-' + model.get( 'id' ) ).prop( 'checked',model.get( 'checked' ) );
			}
		},

		singleSelect: function( event ) {
			var isChecked = $( event.currentTarget ).prop( 'checked' );

			// To avoid infinite loops
			this.model.set( 'checked', isChecked, { silent: true } );

			var hasChecked = false;

			_.each( this.model.collection.models, function( model ) {
				if ( true === model.get( 'checked' ) ) {
					hasChecked = true;
				}
			} );

			if ( hasChecked ) {
				$( '#user-messages-bulk-actions' ).closest( '.bulk-actions-wrap' ).removeClass( 'bp-hide' );
			} else {
				$( '#user-messages-bulk-actions' ).closest( '.bulk-actions-wrap' ).addClass( 'bp-hide' );
			}
		},

		cleanView: function() {
			this.views.view.remove();
		}
	} );

	bp.Views.previewThread = bp.Progenitor.Messages.View.extend( {
		tagName: 'div',
		id: 'thread-preview',
		template  : bp.template( 'bp-messages-preview' ),

		events: {
			'click .actions button' : 'doAction',
			'click .actions a'      : 'doAction'
		},

		initialize: function() {
			this.collection.on( 'change:active', this.setPreview, this );
			this.collection.on( 'change:is_starred', this.updatePreview, this );
			this.collection.on( 'reset', this.emptyPreview, this );
			this.collection.on( 'remove', this.emptyPreview, this );
		},

		render: function() {
			// Only render if we have some content to render
			if ( _.isUndefined( this.model ) || true !== this.model.get( 'active' ) ) {
				return;
			}

			bp.Progenitor.Messages.View.prototype.render.apply( this, arguments );
		},

		setPreview: function( model ) {
			var self = this;

			this.model = model;

			if ( true === model.get( 'unread' ) ) {
				this.model.updateReadState().done( function() {
					self.model.set( 'unread', false );
				} );
			}

			this.render();
		},

		updatePreview: function( model ) {
			if ( true === model.get( 'active' ) ) {
				this.render();
			}
		},

		emptyPreview: function() {
			$( this.el ).html( '' );
		},

		doAction: function( event ) {
			var action = $( event.currentTarget ).data( 'bp-action' ), self = this, options = {}, mid;

			if ( ! action ) {
				return event;
			}

			event.preventDefault();

			var model = this.collection.findWhere( { active: true } );

			if ( ! model.get( 'id' ) ) {
				return;
			}

			mid = model.get( 'id' );

			if ( 'star' === action || 'unstar' === action ) {
				options.data = {
					'star_nonce' : model.get( 'star_nonce' )
				};

				mid = model.get( 'starred_id' );
			}

			this.collection.doAction( action, mid, options ).done( function( response ) {
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );

				if ( 'delete' === action || ( 'starred' === self.collection.options.box && 'unstar' === action ) ) {
					// Remove from the list of messages
					self.collection.remove( model.get( 'id' ) );

					// And Requery
					self.collection.fetch( {
						data : _.pick( self.collection.options, ['box', 'search_terms', 'page'] )
					} );
				} else if ( 'unstar' === action || 'star' === action ) {
					// Update the model attributes--updates the star icon.
					_.each( response.messages, function( updated ) {
						model.set( updated );
					} );
					model.set( _.first( response.messages ) );
				} else if ( response.messages ) {
					model.set( _.first( response.messages ) );
				}
			} ).fail( function( response ) {
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
			} );
		}
	} );

	bp.Views.Pagination = bp.Progenitor.Messages.View.extend( {
		tagName   : 'li',
		className : 'last filter',
		template  :  bp.template( 'bp-messages-paginate' )
	} );

	bp.Views.BulkActions = bp.Progenitor.Messages.View.extend( {
		tagName   : 'div',
		template  :  bp.template( 'bp-bulk-actions' ),

		events : {
			'click #user_messages_select_all' : 'bulkSelect',
			'click .bulk-apply'               : 'doBulkAction'
		},

		bulkSelect: function( event ) {
			var isChecked = $( event.currentTarget ).prop( 'checked' );

			if ( isChecked ) {
				$( this.el ).find( '.bulk-actions-wrap' ).removeClass( 'bp-hide' );
			} else {
				$( this.el ).find( '.bulk-actions-wrap' ).addClass( 'bp-hide' );
			}

			_.each( this.collection.models, function( model ) {
				model.set( 'checked', isChecked );
			} );
		},

		doBulkAction: function( event ) {
			var self = this, options = {}, ids, attr = 'id';

			event.preventDefault();

			var action = $( '#user-messages-bulk-actions' ).val();

			if ( ! action ) {
				return;
			}

			var threads    = this.collection.where( { checked: true } );
			var thread_ids = _.map( threads, function( model ) {
				return model.get( 'id' );
			} );

			// Default to thread ids
			ids = thread_ids;

			// We need to get the starred ids
			if ( 'star' === action || 'unstar' === action ) {
				ids = _.map( threads, function( model ) {
					return model.get( 'starred_id' );
				} );

				if ( 1 === ids.length ) {
					options.data = {
						'star_nonce' : threads[0].get( 'star_nonce' )
					};
				}

				// Map with first message starred in the thread
				attr = 'starred_id';
			}

			// Message id to Thread id
			var m_tid = _.object( _.map( threads, function (model) {
			    return [model.get( attr ), model.get( 'id' )];
			} ) );

			this.collection.doAction( action, ids, options ).done( function( response ) {
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );

				if ( 'delete' === action || ( 'starred' === self.collection.options.box && 'unstar' === action ) ) {
					// Remove from the list of messages
					self.collection.remove( thread_ids );

					// And Requery
					self.collection.fetch( {
						data : _.pick( self.collection.options, ['box', 'search_terms', 'page'] )
					} );
				} else if ( response.messages ) {
					// Update each model attributes
					_.each( response.messages, function( updated, id ) {
						var model = self.collection.get( m_tid[id] );
						model.set( updated );
					} );
				}
			} ).fail( function( response ) {
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
			} );
		}
	} );

	bp.Views.messageFilters = bp.Progenitor.Messages.View.extend( {
		tagName: 'ul',
		template:  bp.template( 'bp-messages-filters' ),

		events : {
			'focus #user_messages_search'       : 'displaySrcBtn',
			'blur #user_messages_search'        : 'hideSrcBtn',
			'search #user_messages_search'      : 'resetSearchTerms',
			'submit #user_messages_search_form' : 'setSearchTerms',
			'click #bp-messages-next-page'      : 'nextPage',
			'click #bp-messages-prev-page'      : 'prevPage'
		},

		initialize: function() {
			this.model.on( 'change', this.filterThreads, this );
			this.options.threads.on( 'sync', this.addPaginatation, this );
		},

		addPaginatation: function( collection ) {
			_.each( this.views._views, function( view ) {
				if ( ! _.isUndefined( view ) ) {
					_.first( view ).remove();
				}
			} );

			this.views.add( new bp.Views.Pagination( { model: new Backbone.Model( collection.options ) } ) );

			this.views.add( '.user-messages-bulk-actions', new bp.Views.BulkActions( {
				model: new Backbone.Model( BP_Progenitor.messages.bulk_actions ),
				collection : collection
			} ) );
		},

		filterThreads: function() {
			bp.Progenitor.Messages.displayFeedback( BP_Progenitor.messages.loading, 'loading' );

			this.options.threads.reset();
			_.extend( this.options.threads.options, _.pick( this.model.attributes, ['box', 'search_terms'] ) );

			this.options.threads.fetch( {
				data    : _.pick( this.model.attributes, ['box', 'search_terms', 'page'] ),
				success : this.threadsFiltered,
				error   : this.threadsFilterError
			} );
		},

		threadsFiltered: function() {
			bp.Progenitor.Messages.removeFeedback();
		},

		threadsFilterError: function( collection, response ) {
			bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
		},

		displaySrcBtn: function( event ) {
			event.preventDefault();

			$( event.target ).closest( 'form' ).find( '[type=submit]' ).addClass('bp-show').removeClass('bp-hide');
		},

		hideSrcBtn: function( event ) {
			event.preventDefault();

			if ( $( event.target ).val() ) {
				return;
			}

			$( event.target ).closest( 'form' ).find( '[type=submit]' ).addClass('bp-hide').removeClass('bp-show');
		},

		resetSearchTerms: function( event ) {
			event.preventDefault();

			if ( ! $( event.target ).val() ) {
				$( event.target ).closest( 'form' ).submit();
			} else {
				$( event.target ).closest( 'form' ).find( '[type=submit]' ).addClass('bp-show').removeClass('bp-hide');
			}
		},

		setSearchTerms: function( event ) {
			event.preventDefault();

			this.model.set( {
				'search_terms': $( event.target ).find( 'input[type=search]' ).val() || '',
				page: 1
			} );
		},

		nextPage: function( event ) {
			event.preventDefault();

			this.model.set( 'page', this.model.get( 'page' ) + 1 );
		},

		prevPage: function( event ) {
			event.preventDefault();

			this.model.set( 'page', this.model.get( 'page' ) - 1 );
		}
	} );

	bp.Views.userMessagesHeader = bp.Progenitor.Messages.View.extend( {
		tagName  : 'div',
		template : bp.template( 'bp-messages-single-header' ),

		events: {
			'click .actions a' : 'doAction'
		},

		doAction: function( event ) {
			var action = $( event.currentTarget ).data( 'bp-action' ), self = this, options = {};

			if ( ! action ) {
				return event;
			}

			event.preventDefault();

			if ( ! this.model.get( 'id' ) ) {
				return;
			}

			if ( 'star' === action || 'unstar' === action ) {
				options.data = {
					'star_nonce' : this.model.get( 'star_nonce' )
				};
			}

			bp.Progenitor.Messages.threads.doAction( action, this.model.get( 'id' ), options ).done( function( response ) {
				// Remove all views
				if ( 'delete' === action ) {
					bp.Progenitor.Messages.clearViews();
				} else if ( response.messages ) {
					self.model.set( _.first( response.messages ) );
				}
				// Display the feedback
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );

			} ).fail( function( response ) {
				bp.Progenitor.Messages.displayFeedback( response.feedback, response.type );
			} );
		}
	} );

	bp.Views.userMessagesEntry = bp.Views.userMessagesHeader.extend( {
		tagName  : 'li',
		template : bp.template( 'bp-messages-single-list' ),

		events: {
			'click [data-bp-action]' : 'doAction'
		},

		initialize: function() {
			this.model.on( 'change:is_starred', this.updateMessage, this );
		},

		updateMessage: function( model ) {
			if ( this.model.get( 'id' ) !== model.get( 'id' ) ) {
				return;
			}

			this.render();
		}
	} );

	bp.Views.userMessages = bp.Progenitor.Messages.View.extend( {
		tagName  : 'div',
		template : bp.template( 'bp-messages-single' ),

		initialize: function() {
			// Load Messages
			this.requestMessages();

			// Init a reply
			this.reply = new bp.Models.messageThread();

			this.collection.on( 'add', this.addMessage, this );

			// Add the editor view
			this.views.add( '#bp-message-content', new bp.Views.messageEditor() );
		},

		events: {
			'click #send_reply_button' : 'sendReply'
		},

		requestMessages: function() {
			var data = {};

			this.collection.reset();

			bp.Progenitor.Messages.displayFeedback( BP_Progenitor.messages.loading, 'loading' );

			if ( _.isUndefined( this.options.thread.attributes ) ) {
				data.id = this.options.thread.id;

			} else {
				data.id        = this.options.thread.get( 'id' );
				data.js_thread = ! _.isEmpty( this.options.thread.get( 'subject' ) );
			}

			this.collection.fetch( {
				data: data,
				success : _.bind( this.messagesFetched, this ),
				error   : this.messagesFetchError
			} );
		},

		messagesFetched: function( collection, response ) {
			if ( ! _.isUndefined( response.thread ) ) {
				this.options.thread = new Backbone.Model( response.thread );
			}

			bp.Progenitor.Messages.removeFeedback();

			this.views.add( '#bp-message-thread-header', new bp.Views.userMessagesHeader( { model: this.options.thread } ) );
		},

		messagesFetchError: function( collection ) {
			console.log( collection );
		},

		addMessage: function( message ) {
			this.views.add( '#bp-message-thread-list', new bp.Views.userMessagesEntry( { model: message } ) );
		},

		addEditor: function() {
			// Load the Editor
			this.views.add( '#bp-message-content', new bp.Views.messageEditor() );
		},

		sendReply: function( event ) {
			event.preventDefault();

			if ( true === this.reply.get( 'sending' ) ) {
				return;
			}

			this.reply.set ( {
				thread_id : this.options.thread.get( 'id' ),
				content   : tinyMCE.activeEditor.getContent(),
				sending   : true
			} );

			this.collection.sync( 'create', _.pick( this.reply.attributes, ['thread_id', 'content' ] ), {
				success : _.bind( this.replySent, this ),
				error   : _.bind( this.replyError, this )
			} );
		},

		replySent: function( response ) {
			var reply = this.collection.parse( response );

			// Reset the form
			tinyMCE.activeEditor.setContent( '' );
			this.reply.set( 'sending', false );

			this.collection.add( _.first( reply ) );
		},

		replyError: function( response ) {
			console.log( response );
		}
	} );

	bp.Progenitor.Messages.Router = Backbone.Router.extend( {
		routes: {
			'compose' : 'composeMessage',
			'view/:id': 'viewMessage',
			'sentbox' : 'sentboxView',
			'starred' : 'starredView',
			'inbox'   : 'inboxView',
			''        : 'inboxView'
		},

		composeMessage: function() {
			bp.Progenitor.Messages.composeView();
		},

		viewMessage: function( thread_id ) {
			if ( ! thread_id ) {
				return;
			}

			// Try to get the corresponding thread
			var thread = bp.Progenitor.Messages.threads.get( thread_id );

			if ( undefined === thread ) {
				thread    = {};
				thread.id = thread_id;
			}

			bp.Progenitor.Messages.singleView( thread );
		},

		sentboxView: function() {
			bp.Progenitor.Messages.box = 'sentbox';
			bp.Progenitor.Messages.threadsView();
		},

		starredView: function() {
			bp.Progenitor.Messages.box = 'starred';
			bp.Progenitor.Messages.threadsView();
		},

		inboxView: function() {
			bp.Progenitor.Messages.box = 'inbox';
			bp.Progenitor.Messages.threadsView();
		}
	} );

	// Launch BP Progenitor Groups
	bp.Progenitor.Messages.start();

} )( bp, jQuery );
