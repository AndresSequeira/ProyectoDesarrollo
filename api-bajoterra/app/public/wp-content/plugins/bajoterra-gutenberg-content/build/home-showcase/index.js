( function ( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var InspectorControls = blockEditor.InspectorControls;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var PanelBody = components.PanelBody;

	blocks.registerBlockType( 'bajoterra/home-showcase', {
		edit: function ( props ) {
			var attributes = props.attributes;

			return el(
				'div',
				{ className: 'bgt-showcase' },
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: 'Contenido', initialOpen: true },
						el( TextControl, {
							label: 'Etiqueta',
							value: attributes.eyebrow,
							onChange: function ( value ) {
								props.setAttributes( { eyebrow: value } );
							},
						} ),
						el( TextControl, {
							label: 'Titulo',
							value: attributes.title,
							onChange: function ( value ) {
								props.setAttributes( { title: value } );
							},
						} ),
						el( TextareaControl, {
							label: 'Texto',
							value: attributes.text,
							onChange: function ( value ) {
								props.setAttributes( { text: value } );
							},
						} )
					)
				),
				el( 'p', { className: 'bgt-showcase__eyebrow' }, attributes.eyebrow ),
				el( 'h2', {}, attributes.title ),
				el( 'p', {}, attributes.text ),
				el(
					'div',
					{ className: 'bgt-showcase__grid' },
					el( 'div', {}, el( 'strong', {}, '01' ), el( 'span', {}, 'Tema hijo' ) ),
					el( 'div', {}, el( 'strong', {}, '02' ), el( 'span', {}, 'Plugins' ) ),
					el( 'div', {}, el( 'strong', {}, '03' ), el( 'span', {}, 'CPT' ) )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components );
