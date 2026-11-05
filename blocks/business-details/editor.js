( function ( blocks, element, components, blockEditor, labels ) {
	'use strict';
	var el = element.createElement;
	var keys = [ 'name', 'address', 'nip', 'regon', 'krs', 'email', 'phone', 'website' ];

	blocks.registerBlockType( 'digicyfr/business-details', {
		title: labels.title,
		description: labels.description,
		icon: 'id-alt',
		category: 'widgets',
		attributes: {
			fields: { type: 'array', default: keys }
		},
		edit: function ( props ) {
			var selected = Array.isArray( props.attributes.fields ) ? props.attributes.fields : keys;
			var toggles = keys.map( function ( key ) {
				return el( components.ToggleControl, {
					key: key,
					label: labels.labels[ key ],
					checked: selected.indexOf( key ) !== -1,
					onChange: function ( checked ) {
						var next = keys.filter( function ( item ) {
							return item === key ? checked : selected.indexOf( item ) !== -1;
						} );
						props.setAttributes( { fields: next } );
					}
				} );
			} );
			var chosen = keys.filter( function ( key ) { return selected.indexOf( key ) !== -1; } );
			return el( element.Fragment, null,
				el( blockEditor.InspectorControls, null,
					el( components.PanelBody, { title: labels.fields, initialOpen: true }, toggles )
				),
				el( components.Placeholder, { icon: 'id-alt', label: labels.title },
					el( 'p', null, chosen.length ? labels.preview : labels.none ),
					chosen.length ? el( 'ul', null, chosen.map( function ( key ) {
						return el( 'li', { key: key }, labels.labels[ key ] );
					} ) ) : null
				)
			);
		},
		save: function () { return null; }
	} );
} )( wp.blocks, wp.element, wp.components, wp.blockEditor, digicyfrBusinessDetailsLabels );
