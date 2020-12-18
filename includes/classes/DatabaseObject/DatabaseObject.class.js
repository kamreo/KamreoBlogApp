class DatabaseObject {

    static ClearList() {
        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ this.name ] ) DatabaseObject.objects[ this.name ] = {};

        jQuery( Object.keys( DatabaseObject.objects[ this.name ] ) ).each( function () {
            var key = this;
            if ( DatabaseObject.objects[ this.name ] ) delete DatabaseObject.objects[ this.name ][ key ];
        } );

        DatabaseObject.objects[ this.name ] = {};
    }

    static GetList( callback = false, force = false, data = {} ) {
        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ this.name ] ) DatabaseObject.objects[ this.name ] = {};

        var databaseObjects = Object.values( DatabaseObject.objects[ this.name ] );

        if ( databaseObjects.length ) {
            if ( callback ) callback( databaseObjects, data );
        } else {
            if ( force ) this.LoadList( callback, data );
        }
    }

    static Set( databaseObject ) {
        if ( !databaseObject ) return false;

        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ this.name ] ) DatabaseObject.objects[ this.name ] = {};

        if ( DatabaseObject.objects[ this.name ][ databaseObject.GetId() ] ) delete DatabaseObject.objects[ this.name ][ databaseObject.GetId() ];
        DatabaseObject.objects[ this.name ][ databaseObject.GetId() ] = databaseObject;

        return DatabaseObject.objects[ this.name ][ databaseObject.GetId() ];
    }

    static Get( objectId, callback ) {
        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ this.name ] ) DatabaseObject.objects[ this.name ] = {};

        if ( DatabaseObject.objects[ this.name ][ objectId ] ) {
            callback( DatabaseObject.objects[ this.name ][ objectId ] );
            return;
        }

        this.Load( objectId, callback );
    }
    static Find( key, value, callback = false ) {
        var object = this;
        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ object.name ] ) DatabaseObject.objects[ object.name ] = {};

        jQuery( Object.keys( DatabaseObject.objects[ object.name ] ) ).each( function () {
            if ( DatabaseObject.objects[ object.name ][ this ].Get( key ) == value ) {
                callback( DatabaseObject.objects[ object.name ][ objectId ] );
            }
        } );
    }

    static Load( objectId, callback = false ) {
        var prototype = this;

        Ajax.Post( {
            data: {
                action: prototype.calls.GetData,
                objectId: objectId,
            },
            success: function ( result ) {
                var data = JSON.tryParse( result );
                if ( data ) {
                    var databeObject = new prototype( data );
                    if ( callback ) callback( prototype.Set( databeObject ) );
                    return;
                }
                if ( callback ) callback( false );
            }
        } );
    }
    static LoadList( callback = false, data = {} ) {
        var prototype = this;
        Ajax.Post( {
            data: {
                action: prototype.calls.GetDataList,
            },
            success: function ( result ) {
                var databaseObjects = new Array();

                var dataList = JSON.tryParse( result );
                if ( dataList ) {
                    jQuery( dataList ).each( function () {
                        var data = this;
                        var databeObject = new prototype( data );
                        databaseObjects.push( prototype.Set( databeObject ) );
                    } );
                }

                if ( callback ) callback( databaseObjects, data );
            }
        } );
    }
    static LoadListByIds( objectIds, callback = false ) {
        if ( Array.isArray( objectIds ) ) objectIds = objectIds.join( ',' );
        var prototype = this;
        Ajax.Post( {
            data: {
                action: prototype.calls.GetDataListById,
                objectIds: objectIds
            },
            success: function ( result ) {
                var databaseObjects = new Array();

                var dataList = JSON.tryParse( result );
                if ( dataList ) {
                    jQuery( dataList ).each( function () {
                        var data = this;
                        var databeObject = new prototype( data );
                        databaseObjects.push( prototype.Set( databeObject ) );
                    } );
                }

                if ( callback ) callback( databaseObjects );
            }
        } );
    }

    static FromResult( result = false, json = true ) {
        var prototype = this;
        if ( !result ) return null;
        if ( !json ) return prototype.Set( new prototype( result ) );
        var data = JSON.tryParse( result );
        if ( !data ) return null;
        return prototype.Set( new prototype( data ) );
    }

    static FromResults( results = false ) {
        var prototype = this;
        if ( !results ) return new Array();
        var dataList = JSON.tryParse( results );
        if ( !dataList ) return new Array();
        var databaseObjects = new Array();
        jQuery( dataList ).each( function () {
            var data = this;
            var databaseObject = prototype.FromResult( data, false );
            if ( databaseObject ) databaseObjects.push( databaseObject );
        } );
        return databaseObjects;
    }

    Clear() {
        var databaseObject = this;
        var prototype = databaseObject.constructor;

        if ( !DatabaseObject.objects ) DatabaseObject.objects = {};
        if ( !DatabaseObject.objects[ prototype.name ] ) DatabaseObject.objects[ prototype.name ] = {};

        jQuery( Object.keys( DatabaseObject.objects[ prototype.name ] ) ).each( function () {
            var key = this;
            if ( DatabaseObject.objects[ prototype.name ][ key ] == databaseObject.GetId() ) {
                delete DatabaseObject.objects[ prototype.name ][ key ];
            }
        } );
    }

    FindElements( elementClass = false, attribute = false, attributeValue = false ) {
        if ( !attributeValue ) attributeValue = this.GetId();
        if ( attribute && attributeValue ) return jQuery( elementClass + '[' + attribute + '="' + attributeValue + '"]' );
        if ( attribute ) return jQuery( elementClass + '[' + attribute + ']' );
        jQuery( elementClass );
    }

    Call( call, callback = false ) {
        var databaseObject = this;
        Ajax.Post( {
            data: {
                action: call,
                objectId: databaseObject.GetId(),
            },
            success: function ( result ) {
                if ( callback ) callback( result );
            }
        } );
    }

    constructor ( data ) {
        var databaseObject = this;
        jQuery( Object.keys( data ) ).each( function () {
            var key = this;
            var value = data[ key ];
            if ( !value ) return;
            if ( key === 'attachments' || key === 'targetAttachments' ) {
                jQuery( Object.keys( value ) ).each( function () {
                    var objectKey = this;
                    value[ objectKey ] = new Attachment( value[ objectKey ] );
                } );
            } else if ( typeof ( value ) === 'object' ) {
                switch ( value.object ) {
                    case 'User': data[ key ] = new User( value ); return;
                    case 'File': data[ key ] = new gFile( value ); return;
                    case 'Attachment': data[ key ] = new Attachment( value ); return;
                    case 'Comment': data[ key ] = new gComment( value ); return;
                    case 'CommentReaction': data[ key ] = new CommentReaction( value ); return;
                    case 'Category': data[ key ] = new Category( value ); return;
                    case 'Calendar': data[ key ] = new Calendar( value ); return;
                    case 'CalendarEvent': data[ key ] = new CalendarEvent( value ); return;
                    case 'Order': data[ key ] = new Order( value ); return;
                    case 'OrderAction': data[ key ] = new OrderAction( value ); return;
                    case 'Task': data[ key ] = new gTask( value ); return;
                    case 'TaskStage': data[ key ] = new gTaskStage( value ); return;
                    case 'ChecklistStage': data[ key ] = new ChecklistStage( value ); return;
                    case 'ChecklistStageComment': data[ key ] = new ChecklistStageComment( value ); return;
                    case 'UserChecklist': data[ key ] = new UserChecklist( value ); return;
                    case 'TestBoxRaport': data[ key ] = new TestBoxRaport( value ); return;
                    case 'Conversation': data[ key ] = new Conversation( value ); return;
                    case 'FileCloudFile': data[ key ] = new FileCloudFile( value ); return;
                    case 'FileCloudFileUser': data[ key ] = new FileCloudFileUser( value ); return;
                    case 'FileCloudFolder': data[ key ] = new FileCloudFolder( value ); return;
                    case 'FileCloudFolderUser': data[ key ] = new FileCloudFolderUser( value ); return;
                }
            }
        } );
        databaseObject.data = data;
    }

    IsOwner() { if ( this.GetUser() ) return this.GetUser().IsMe(); return false; }

    Get( variable ) { return this.data && this.data[ variable ] ? this.data[ variable ] : null; }
    Set( variable, value ) { this.data[ variable ] = value; return this.Get( variable ); }
    GetId() { return this.Get( 'id' ); }
    GetClass() { return this.Get( 'class' ); }
    GetType() { return this.Get( 'type' ); }
    GetName() { return this.Get( 'name' ) ? this.Get( 'name' ) : language.no_name; }
    GetDescription() { return this.Get( 'description' ); }
    GetStatus() { return this.Get( 'status' ); }
    GetUser( user = false ) { return this.Get( user ? user : 'user' ); }
    GetFile() { return this.Get( 'file' ); }
    GetDate( variable = false ) { if ( !variable ) variable = 'date'; return gDate.fromString( this.data[ variable ] ); }
    Load( callback = false ) { this.prototype.Load( this.GetId(), callback ); }
    GetAttachments( callback = false, attachments = 'attachments' ) { if ( callback ) callback( this.Get( attachments ) ); }
    GetColor( color = 'color' ) { return Color.fromString( this.Get( color ) ); }
    GetCategory( category = 'category' ) { return this.Get( category ); }

    GetInitials() { return Utility.Initials( this.GetName() ); }

    HasDate( date = 'date' ) { return this.Get( date ) && this.GetDate( date ).timestamp() >= 0; }
}