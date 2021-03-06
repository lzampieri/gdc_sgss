window.EditorJS = require( '@editorjs/editorjs' );

class MyComponentClass {
    static get isReadOnlySupported() {
        return true;
    }

    constructor({data, readOnly}){
        this.data = data;
        this.readOnly = readOnly;
    }

    render( className ){
        const input = document.createElement('div');
        input.innerHTML = this.data && this.data.content ? this.data.content : '';
        
        if( !this.readOnly ) input.contentEditable = true;
        input.className = className;
        return input;
    }
  
    save(blockContent){
        return {
            content: blockContent.innerHTML
        }
    }    
};

class CenteredSmall extends MyComponentClass {
    static get toolbox() {
        return {
            title: 'Centrato e piccolo',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0.2 -0.3 9 11.4" width="12" height="14"><path d="M0 2.77V.92A1 1 0 01.2.28C.35.1.56 0 .83 0h7.66c.28.01.48.1.63.28.14.17.21.38.21.64v1.85c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26a1 1 0 01-.21-.66V1.69H5.6v7.58h.5c.25 0 .45.08.6.23.17.16.25.35.25.6s-.08.45-.24.6a.87.87 0 01-.62.22H3.21a.87.87 0 01-.61-.22.78.78 0 01-.24-.6c0-.25.08-.44.24-.6a.85.85 0 01.61-.23h.5V1.7H1.73v1.08c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26A1 1 0 010 2.77z"></path></svg>'
        };
    }

    render() {
        return super.render( "w-full text-center text-sm" )
    }

}

class CenteredUnderlined extends MyComponentClass {
    static get toolbox() {
        return {
            title: 'Centrato e sottolineato',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0.2 -0.3 9 11.4" width="12" height="14"><path d="M0 2.77V.92A1 1 0 01.2.28C.35.1.56 0 .83 0h7.66c.28.01.48.1.63.28.14.17.21.38.21.64v1.85c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26a1 1 0 01-.21-.66V1.69H5.6v7.58h.5c.25 0 .45.08.6.23.17.16.25.35.25.6s-.08.45-.24.6a.87.87 0 01-.62.22H3.21a.87.87 0 01-.61-.22.78.78 0 01-.24-.6c0-.25.08-.44.24-.6a.85.85 0 01.61-.23h.5V1.7H1.73v1.08c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26A1 1 0 010 2.77z"></path></svg>'
        };
    }

    render() {
        return super.render( "underline w-full text-lg text-center" )
    }

}

class Bounded extends MyComponentClass {
    static get toolbox() {
        return {
            title: 'Ingabbiato',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0.2 -0.3 9 11.4" width="12" height="14"><path d="M0 2.77V.92A1 1 0 01.2.28C.35.1.56 0 .83 0h7.66c.28.01.48.1.63.28.14.17.21.38.21.64v1.85c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26a1 1 0 01-.21-.66V1.69H5.6v7.58h.5c.25 0 .45.08.6.23.17.16.25.35.25.6s-.08.45-.24.6a.87.87 0 01-.62.22H3.21a.87.87 0 01-.61-.22.78.78 0 01-.24-.6c0-.25.08-.44.24-.6a.85.85 0 01.61-.23h.5V1.7H1.73v1.08c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26A1 1 0 010 2.77z"></path></svg>'
        };
    }

    render() {
        return super.render( "w-4/5 border-x-2 border-bgblack mx-auto my-4 px-4" )
    }

}



window.Tools = {
    centeredsmall: { class: CenteredSmall, inlineToolbar: true },
    centeredunderlined: { class: CenteredUnderlined, inlineToolbar: true },
    bounded: { class: Bounded, inlineToolbar: true },
}