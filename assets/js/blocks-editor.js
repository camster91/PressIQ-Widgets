/**
 * AC Starter Toolkit - Block Editor Scripts
 *
 * Enhances server-side rendered blocks with InspectorControls
 * for the WordPress Site Editor (Full Site Editing).
 *
 * @package AC_Starter_Toolkit
 */

( function( wp ) {
    'use strict';

    var el             = wp.element.createElement;
    var __             = wp.i18n.__;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps   = wp.blockEditor.useBlockProps;
    var PanelBody      = wp.components.PanelBody;
    var TextControl    = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var ToggleControl  = wp.components.ToggleControl;
    var SelectControl  = wp.components.SelectControl;
    var RangeControl   = wp.components.RangeControl;
    var Button         = wp.components.Button;
    var ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender;
    var Fragment       = wp.element.Fragment;

    /* =========================================================================
       Helper: Repeater-like item management
       ========================================================================= */

    function RepeaterItems( props ) {
        var items    = props.items || [];
        var onChange = props.onChange;
        var renderItem = props.renderItem;
        var addLabel = props.addLabel || __( 'Add Item', 'ac-starter-toolkit' );
        var newItem  = props.newItem;

        function updateItem( index, key, value ) {
            var updated = items.map( function( item, i ) {
                if ( i === index ) {
                    var copy = Object.assign( {}, item );
                    copy[ key ] = value;
                    return copy;
                }
                return item;
            } );
            onChange( updated );
        }

        function removeItem( index ) {
            onChange( items.filter( function( _, i ) { return i !== index; } ) );
        }

        function addItem() {
            onChange( items.concat( [ Object.assign( {}, newItem ) ] ) );
        }

        function moveItem( index, direction ) {
            var updated = [].concat( items );
            var target  = index + direction;
            if ( target < 0 || target >= updated.length ) return;
            var temp = updated[ index ];
            updated[ index ] = updated[ target ];
            updated[ target ] = temp;
            onChange( updated );
        }

        return el( Fragment, {},
            items.map( function( item, index ) {
                return el( PanelBody, {
                    key: index,
                    title: renderItem.title ? renderItem.title( item, index ) : ( __( 'Item', 'ac-starter-toolkit' ) + ' ' + ( index + 1 ) ),
                    initialOpen: false
                },
                    renderItem.controls( item, index, updateItem ),
                    el( 'div', { style: { display: 'flex', gap: '8px', marginTop: '12px' } },
                        el( Button, { isSmall: true, variant: 'secondary', onClick: function() { moveItem( index, -1 ); }, disabled: index === 0 }, '↑' ),
                        el( Button, { isSmall: true, variant: 'secondary', onClick: function() { moveItem( index, 1 ); }, disabled: index === items.length - 1 }, '↓' ),
                        el( Button, { isSmall: true, isDestructive: true, onClick: function() { removeItem( index ); } }, __( 'Remove', 'ac-starter-toolkit' ) )
                    )
                );
            } ),
            el( Button, { variant: 'secondary', onClick: addItem, style: { marginTop: '12px' } }, addLabel )
        );
    }

    /* =========================================================================
       Block: Accordion
       ========================================================================= */

    registerBlockType( 'pressiq/accordion', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Settings', 'ac-starter-toolkit' ) },
                        el( ToggleControl, {
                            label: __( 'Collapse Others', 'ac-starter-toolkit' ),
                            checked: attributes.collapseOthers,
                            onChange: function( val ) { setAttributes( { collapseOthers: val } ); }
                        } ),
                        el( SelectControl, {
                            label: __( 'Title Tag', 'ac-starter-toolkit' ),
                            value: attributes.titleTag,
                            options: [
                                { label: 'DIV', value: 'div' },
                                { label: 'H2', value: 'h2' },
                                { label: 'H3', value: 'h3' },
                                { label: 'H4', value: 'h4' },
                                { label: 'H5', value: 'h5' },
                                { label: 'H6', value: 'h6' }
                            ],
                            onChange: function( val ) { setAttributes( { titleTag: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Items', 'ac-starter-toolkit' ), initialOpen: true },
                        el( RepeaterItems, {
                            items: attributes.items,
                            onChange: function( items ) { setAttributes( { items: items } ); },
                            addLabel: __( 'Add Accordion Item', 'ac-starter-toolkit' ),
                            newItem: { title: __( 'New Item', 'ac-starter-toolkit' ), content: '', open: false },
                            renderItem: {
                                title: function( item ) { return item.title || __( 'Untitled', 'ac-starter-toolkit' ); },
                                controls: function( item, index, update ) {
                                    return el( Fragment, {},
                                        el( TextControl, {
                                            label: __( 'Title', 'ac-starter-toolkit' ),
                                            value: item.title,
                                            onChange: function( val ) { update( index, 'title', val ); }
                                        } ),
                                        el( TextareaControl, {
                                            label: __( 'Content', 'ac-starter-toolkit' ),
                                            value: item.content,
                                            onChange: function( val ) { update( index, 'content', val ); }
                                        } ),
                                        el( ToggleControl, {
                                            label: __( 'Open by Default', 'ac-starter-toolkit' ),
                                            checked: item.open,
                                            onChange: function( val ) { update( index, 'open', val ); }
                                        } )
                                    );
                                }
                            }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/accordion', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Tabs
       ========================================================================= */

    registerBlockType( 'pressiq/tabs', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Settings', 'ac-starter-toolkit' ) },
                        el( SelectControl, {
                            label: __( 'Layout', 'ac-starter-toolkit' ),
                            value: attributes.layout,
                            options: [
                                { label: __( 'Horizontal', 'ac-starter-toolkit' ), value: 'horizontal' },
                                { label: __( 'Vertical', 'ac-starter-toolkit' ), value: 'vertical' }
                            ],
                            onChange: function( val ) { setAttributes( { layout: val } ); }
                        } ),
                        el( RangeControl, {
                            label: __( 'Default Active Tab', 'ac-starter-toolkit' ),
                            value: attributes.defaultActive,
                            min: 1,
                            max: 10,
                            onChange: function( val ) { setAttributes( { defaultActive: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Tabs', 'ac-starter-toolkit' ), initialOpen: true },
                        el( RepeaterItems, {
                            items: attributes.tabs,
                            onChange: function( tabs ) { setAttributes( { tabs: tabs } ); },
                            addLabel: __( 'Add Tab', 'ac-starter-toolkit' ),
                            newItem: { title: __( 'New Tab', 'ac-starter-toolkit' ), content: '' },
                            renderItem: {
                                title: function( item ) { return item.title || __( 'Untitled', 'ac-starter-toolkit' ); },
                                controls: function( item, index, update ) {
                                    return el( Fragment, {},
                                        el( TextControl, {
                                            label: __( 'Title', 'ac-starter-toolkit' ),
                                            value: item.title,
                                            onChange: function( val ) { update( index, 'title', val ); }
                                        } ),
                                        el( TextareaControl, {
                                            label: __( 'Content', 'ac-starter-toolkit' ),
                                            value: item.content,
                                            onChange: function( val ) { update( index, 'content', val ); }
                                        } )
                                    );
                                }
                            }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/tabs', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Team Member
       ========================================================================= */

    registerBlockType( 'pressiq/team-member', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var MediaUpload = wp.blockEditor.MediaUpload;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Content', 'ac-starter-toolkit' ) },
                        el( TextControl, {
                            label: __( 'Name', 'ac-starter-toolkit' ),
                            value: attributes.name,
                            onChange: function( val ) { setAttributes( { name: val } ); }
                        } ),
                        el( SelectControl, {
                            label: __( 'Name Tag', 'ac-starter-toolkit' ),
                            value: attributes.nameTag,
                            options: [
                                { label: 'H1', value: 'h1' }, { label: 'H2', value: 'h2' },
                                { label: 'H3', value: 'h3' }, { label: 'H4', value: 'h4' },
                                { label: 'H5', value: 'h5' }, { label: 'H6', value: 'h6' },
                                { label: 'DIV', value: 'div' }
                            ],
                            onChange: function( val ) { setAttributes( { nameTag: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Role / Position', 'ac-starter-toolkit' ),
                            value: attributes.role,
                            onChange: function( val ) { setAttributes( { role: val } ); }
                        } ),
                        el( TextareaControl, {
                            label: __( 'Bio', 'ac-starter-toolkit' ),
                            value: attributes.bio,
                            onChange: function( val ) { setAttributes( { bio: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Link URL', 'ac-starter-toolkit' ),
                            value: attributes.linkUrl,
                            onChange: function( val ) { setAttributes( { linkUrl: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Image', 'ac-starter-toolkit' ), initialOpen: false },
                        el( MediaUpload, {
                            onSelect: function( media ) {
                                setAttributes( {
                                    imageId: media.id,
                                    imageUrl: media.url,
                                    imageAlt: media.alt || attributes.name
                                } );
                            },
                            allowedTypes: [ 'image' ],
                            value: attributes.imageId,
                            render: function( obj ) {
                                return el( Fragment, {},
                                    attributes.imageUrl
                                        ? el( 'div', {},
                                            el( 'img', { src: attributes.imageUrl, style: { maxWidth: '100%', marginBottom: '8px' } } ),
                                            el( Button, { onClick: function() { setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ); }, isDestructive: true, isSmall: true }, __( 'Remove Image', 'ac-starter-toolkit' ) )
                                        )
                                        : null,
                                    el( Button, { onClick: obj.open, variant: 'secondary' }, attributes.imageUrl ? __( 'Replace Image', 'ac-starter-toolkit' ) : __( 'Select Image', 'ac-starter-toolkit' ) )
                                );
                            }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Layout', 'ac-starter-toolkit' ), initialOpen: false },
                        el( SelectControl, {
                            label: __( 'Layout', 'ac-starter-toolkit' ),
                            value: attributes.layout,
                            options: [
                                { label: __( 'Card (Stacked)', 'ac-starter-toolkit' ), value: 'card' },
                                { label: __( 'Horizontal', 'ac-starter-toolkit' ), value: 'horizontal' },
                                { label: __( 'Image Overlay', 'ac-starter-toolkit' ), value: 'overlay' }
                            ],
                            onChange: function( val ) { setAttributes( { layout: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Social Links', 'ac-starter-toolkit' ), initialOpen: false },
                        el( RepeaterItems, {
                            items: attributes.socialLinks,
                            onChange: function( links ) { setAttributes( { socialLinks: links } ); },
                            addLabel: __( 'Add Social Link', 'ac-starter-toolkit' ),
                            newItem: { network: 'website', url: '#' },
                            renderItem: {
                                title: function( item ) { return item.network ? item.network.charAt(0).toUpperCase() + item.network.slice(1) : 'Link'; },
                                controls: function( item, index, update ) {
                                    return el( Fragment, {},
                                        el( SelectControl, {
                                            label: __( 'Network', 'ac-starter-toolkit' ),
                                            value: item.network,
                                            options: [
                                                { label: 'Facebook', value: 'facebook' },
                                                { label: 'Twitter/X', value: 'twitter' },
                                                { label: 'Instagram', value: 'instagram' },
                                                { label: 'LinkedIn', value: 'linkedin' },
                                                { label: 'YouTube', value: 'youtube' },
                                                { label: 'TikTok', value: 'tiktok' },
                                                { label: 'Pinterest', value: 'pinterest' },
                                                { label: 'GitHub', value: 'github' },
                                                { label: 'Dribbble', value: 'dribbble' },
                                                { label: 'Behance', value: 'behance' },
                                                { label: 'Email', value: 'email' },
                                                { label: 'Website', value: 'website' }
                                            ],
                                            onChange: function( val ) { update( index, 'network', val ); }
                                        } ),
                                        el( TextControl, {
                                            label: __( 'URL', 'ac-starter-toolkit' ),
                                            value: item.url,
                                            onChange: function( val ) { update( index, 'url', val ); }
                                        } )
                                    );
                                }
                            }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/team-member', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Pricing Table
       ========================================================================= */

    registerBlockType( 'pressiq/pricing-table', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Content', 'ac-starter-toolkit' ) },
                        el( TextControl, {
                            label: __( 'Title', 'ac-starter-toolkit' ),
                            value: attributes.title,
                            onChange: function( val ) { setAttributes( { title: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Subtitle', 'ac-starter-toolkit' ),
                            value: attributes.subtitle,
                            onChange: function( val ) { setAttributes( { subtitle: val } ); }
                        } ),
                        el( ToggleControl, {
                            label: __( 'Featured', 'ac-starter-toolkit' ),
                            checked: attributes.featured,
                            onChange: function( val ) { setAttributes( { featured: val } ); }
                        } ),
                        attributes.featured && el( TextControl, {
                            label: __( 'Ribbon Text', 'ac-starter-toolkit' ),
                            value: attributes.ribbonText,
                            onChange: function( val ) { setAttributes( { ribbonText: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Pricing', 'ac-starter-toolkit' ), initialOpen: false },
                        el( TextControl, {
                            label: __( 'Currency', 'ac-starter-toolkit' ),
                            value: attributes.currency,
                            onChange: function( val ) { setAttributes( { currency: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Price', 'ac-starter-toolkit' ),
                            value: attributes.price,
                            onChange: function( val ) { setAttributes( { price: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Original Price', 'ac-starter-toolkit' ),
                            value: attributes.originalPrice,
                            onChange: function( val ) { setAttributes( { originalPrice: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Period', 'ac-starter-toolkit' ),
                            value: attributes.period,
                            onChange: function( val ) { setAttributes( { period: val } ); }
                        } ),
                        el( SelectControl, {
                            label: __( 'Currency Position', 'ac-starter-toolkit' ),
                            value: attributes.currencyPosition,
                            options: [
                                { label: __( 'Before Price', 'ac-starter-toolkit' ), value: 'before' },
                                { label: __( 'After Price', 'ac-starter-toolkit' ), value: 'after' }
                            ],
                            onChange: function( val ) { setAttributes( { currencyPosition: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Features', 'ac-starter-toolkit' ), initialOpen: false },
                        el( RepeaterItems, {
                            items: attributes.features,
                            onChange: function( features ) { setAttributes( { features: features } ); },
                            addLabel: __( 'Add Feature', 'ac-starter-toolkit' ),
                            newItem: { text: __( 'New Feature', 'ac-starter-toolkit' ), available: true },
                            renderItem: {
                                title: function( item ) { return ( item.available ? '✓ ' : '✗ ' ) + ( item.text || '' ); },
                                controls: function( item, index, update ) {
                                    return el( Fragment, {},
                                        el( TextControl, {
                                            label: __( 'Feature Text', 'ac-starter-toolkit' ),
                                            value: item.text,
                                            onChange: function( val ) { update( index, 'text', val ); }
                                        } ),
                                        el( ToggleControl, {
                                            label: __( 'Available', 'ac-starter-toolkit' ),
                                            checked: item.available,
                                            onChange: function( val ) { update( index, 'available', val ); }
                                        } )
                                    );
                                }
                            }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Button', 'ac-starter-toolkit' ), initialOpen: false },
                        el( TextControl, {
                            label: __( 'Button Text', 'ac-starter-toolkit' ),
                            value: attributes.buttonText,
                            onChange: function( val ) { setAttributes( { buttonText: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Button URL', 'ac-starter-toolkit' ),
                            value: attributes.buttonUrl,
                            onChange: function( val ) { setAttributes( { buttonUrl: val } ); }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/pricing-table', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Testimonial
       ========================================================================= */

    registerBlockType( 'pressiq/testimonial', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var MediaUpload = wp.blockEditor.MediaUpload;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Content', 'ac-starter-toolkit' ) },
                        el( TextareaControl, {
                            label: __( 'Testimonial', 'ac-starter-toolkit' ),
                            value: attributes.content,
                            onChange: function( val ) { setAttributes( { content: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Name', 'ac-starter-toolkit' ),
                            value: attributes.name,
                            onChange: function( val ) { setAttributes( { name: val } ); }
                        } ),
                        el( TextControl, {
                            label: __( 'Title / Company', 'ac-starter-toolkit' ),
                            value: attributes.title,
                            onChange: function( val ) { setAttributes( { title: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Image', 'ac-starter-toolkit' ), initialOpen: false },
                        el( MediaUpload, {
                            onSelect: function( media ) {
                                setAttributes( { imageId: media.id, imageUrl: media.url } );
                            },
                            allowedTypes: [ 'image' ],
                            value: attributes.imageId,
                            render: function( obj ) {
                                return el( Fragment, {},
                                    attributes.imageUrl
                                        ? el( 'div', {},
                                            el( 'img', { src: attributes.imageUrl, style: { maxWidth: '80px', borderRadius: '50%', marginBottom: '8px' } } ),
                                            el( Button, { onClick: function() { setAttributes( { imageId: 0, imageUrl: '' } ); }, isDestructive: true, isSmall: true }, __( 'Remove', 'ac-starter-toolkit' ) )
                                        )
                                        : null,
                                    el( Button, { onClick: obj.open, variant: 'secondary' }, attributes.imageUrl ? __( 'Replace', 'ac-starter-toolkit' ) : __( 'Select Image', 'ac-starter-toolkit' ) )
                                );
                            }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Rating & Layout', 'ac-starter-toolkit' ), initialOpen: false },
                        el( ToggleControl, {
                            label: __( 'Show Rating', 'ac-starter-toolkit' ),
                            checked: attributes.showRating,
                            onChange: function( val ) { setAttributes( { showRating: val } ); }
                        } ),
                        attributes.showRating && el( RangeControl, {
                            label: __( 'Rating', 'ac-starter-toolkit' ),
                            value: attributes.rating,
                            min: 1,
                            max: 5,
                            step: 0.5,
                            onChange: function( val ) { setAttributes( { rating: val } ); }
                        } ),
                        el( SelectControl, {
                            label: __( 'Layout', 'ac-starter-toolkit' ),
                            value: attributes.layout,
                            options: [
                                { label: __( 'Default', 'ac-starter-toolkit' ), value: 'default' },
                                { label: __( 'Bubble', 'ac-starter-toolkit' ), value: 'bubble' },
                                { label: __( 'Side', 'ac-starter-toolkit' ), value: 'side' }
                            ],
                            onChange: function( val ) { setAttributes( { layout: val } ); }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/testimonial', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Countdown Timer
       ========================================================================= */

    registerBlockType( 'pressiq/countdown', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Timer Settings', 'ac-starter-toolkit' ) },
                        el( SelectControl, {
                            label: __( 'Countdown Type', 'ac-starter-toolkit' ),
                            value: attributes.countdownType,
                            options: [
                                { label: __( 'Due Date', 'ac-starter-toolkit' ), value: 'due_date' },
                                { label: __( 'Evergreen', 'ac-starter-toolkit' ), value: 'evergreen' }
                            ],
                            onChange: function( val ) { setAttributes( { countdownType: val } ); }
                        } ),
                        attributes.countdownType === 'due_date' && el( TextControl, {
                            label: __( 'Due Date (YYYY-MM-DD HH:MM)', 'ac-starter-toolkit' ),
                            value: attributes.dueDate,
                            onChange: function( val ) { setAttributes( { dueDate: val } ); },
                            help: __( 'Format: 2026-12-31 23:59', 'ac-starter-toolkit' )
                        } ),
                        attributes.countdownType === 'evergreen' && el( RangeControl, {
                            label: __( 'Hours', 'ac-starter-toolkit' ),
                            value: attributes.evergreenHours,
                            min: 0,
                            max: 720,
                            onChange: function( val ) { setAttributes( { evergreenHours: val } ); }
                        } ),
                        attributes.countdownType === 'evergreen' && el( RangeControl, {
                            label: __( 'Minutes', 'ac-starter-toolkit' ),
                            value: attributes.evergreenMinutes,
                            min: 0,
                            max: 59,
                            onChange: function( val ) { setAttributes( { evergreenMinutes: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Display', 'ac-starter-toolkit' ), initialOpen: false },
                        el( ToggleControl, { label: __( 'Show Days', 'ac-starter-toolkit' ), checked: attributes.showDays, onChange: function( val ) { setAttributes( { showDays: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Hours', 'ac-starter-toolkit' ), checked: attributes.showHours, onChange: function( val ) { setAttributes( { showHours: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Minutes', 'ac-starter-toolkit' ), checked: attributes.showMinutes, onChange: function( val ) { setAttributes( { showMinutes: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Seconds', 'ac-starter-toolkit' ), checked: attributes.showSeconds, onChange: function( val ) { setAttributes( { showSeconds: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Labels', 'ac-starter-toolkit' ), checked: attributes.showLabels, onChange: function( val ) { setAttributes( { showLabels: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Separator', 'ac-starter-toolkit' ), checked: attributes.showSeparator, onChange: function( val ) { setAttributes( { showSeparator: val } ); } } )
                    ),
                    el( PanelBody, { title: __( 'Labels', 'ac-starter-toolkit' ), initialOpen: false },
                        el( TextControl, { label: __( 'Days Label', 'ac-starter-toolkit' ), value: attributes.labelDays, onChange: function( val ) { setAttributes( { labelDays: val } ); } } ),
                        el( TextControl, { label: __( 'Hours Label', 'ac-starter-toolkit' ), value: attributes.labelHours, onChange: function( val ) { setAttributes( { labelHours: val } ); } } ),
                        el( TextControl, { label: __( 'Minutes Label', 'ac-starter-toolkit' ), value: attributes.labelMinutes, onChange: function( val ) { setAttributes( { labelMinutes: val } ); } } ),
                        el( TextControl, { label: __( 'Seconds Label', 'ac-starter-toolkit' ), value: attributes.labelSeconds, onChange: function( val ) { setAttributes( { labelSeconds: val } ); } } )
                    ),
                    el( PanelBody, { title: __( 'Expire Action', 'ac-starter-toolkit' ), initialOpen: false },
                        el( SelectControl, {
                            label: __( 'On Expire', 'ac-starter-toolkit' ),
                            value: attributes.expireAction,
                            options: [
                                { label: __( 'Hide', 'ac-starter-toolkit' ), value: 'hide' },
                                { label: __( 'Show Message', 'ac-starter-toolkit' ), value: 'message' }
                            ],
                            onChange: function( val ) { setAttributes( { expireAction: val } ); }
                        } ),
                        attributes.expireAction === 'message' && el( TextControl, {
                            label: __( 'Expire Message', 'ac-starter-toolkit' ),
                            value: attributes.expireMessage,
                            onChange: function( val ) { setAttributes( { expireMessage: val } ); }
                        } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/countdown', attributes: attributes } )
                )
            );
        }
    } );

    /* =========================================================================
       Block: Post Filter
       ========================================================================= */

    registerBlockType( 'pressiq/post-filter', {
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Query Settings', 'ac-starter-toolkit' ) },
                        el( TextControl, {
                            label: __( 'Query ID', 'ac-starter-toolkit' ),
                            value: attributes.queryId,
                            onChange: function( val ) { setAttributes( { queryId: val } ); },
                            help: __( 'Unique ID for this filter group.', 'ac-starter-toolkit' )
                        } ),
                        el( SelectControl, {
                            label: __( 'Post Type', 'ac-starter-toolkit' ),
                            value: attributes.postType,
                            options: [
                                { label: 'Posts', value: 'post' },
                                { label: 'Pages', value: 'page' },
                                { label: 'Products', value: 'product' }
                            ],
                            onChange: function( val ) { setAttributes( { postType: val } ); }
                        } ),
                        el( RangeControl, {
                            label: __( 'Posts Per Page', 'ac-starter-toolkit' ),
                            value: attributes.postsPerPage,
                            min: 1,
                            max: 50,
                            onChange: function( val ) { setAttributes( { postsPerPage: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Filters', 'ac-starter-toolkit' ), initialOpen: false },
                        el( ToggleControl, {
                            label: __( 'Show Search', 'ac-starter-toolkit' ),
                            checked: attributes.showSearch,
                            onChange: function( val ) { setAttributes( { showSearch: val } ); }
                        } ),
                        attributes.showSearch && el( TextControl, {
                            label: __( 'Search Placeholder', 'ac-starter-toolkit' ),
                            value: attributes.searchPlaceholder,
                            onChange: function( val ) { setAttributes( { searchPlaceholder: val } ); }
                        } ),
                        el( ToggleControl, {
                            label: __( 'Show Sort', 'ac-starter-toolkit' ),
                            checked: attributes.showSort,
                            onChange: function( val ) { setAttributes( { showSort: val } ); }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Layout', 'ac-starter-toolkit' ), initialOpen: false },
                        el( SelectControl, {
                            label: __( 'Layout', 'ac-starter-toolkit' ),
                            value: attributes.layout,
                            options: [
                                { label: __( 'Grid', 'ac-starter-toolkit' ), value: 'grid' },
                                { label: __( 'List', 'ac-starter-toolkit' ), value: 'list' }
                            ],
                            onChange: function( val ) { setAttributes( { layout: val } ); }
                        } ),
                        attributes.layout === 'grid' && el( RangeControl, {
                            label: __( 'Columns', 'ac-starter-toolkit' ),
                            value: attributes.columns,
                            min: 1,
                            max: 4,
                            onChange: function( val ) { setAttributes( { columns: val } ); }
                        } ),
                        el( ToggleControl, { label: __( 'Show Thumbnail', 'ac-starter-toolkit' ), checked: attributes.showThumbnail, onChange: function( val ) { setAttributes( { showThumbnail: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Excerpt', 'ac-starter-toolkit' ), checked: attributes.showExcerpt, onChange: function( val ) { setAttributes( { showExcerpt: val } ); } } ),
                        el( ToggleControl, { label: __( 'Show Meta', 'ac-starter-toolkit' ), checked: attributes.showMeta, onChange: function( val ) { setAttributes( { showMeta: val } ); } } )
                    )
                ),
                el( 'div', useBlockProps(),
                    el( ServerSideRender, { block: 'pressiq/post-filter', attributes: attributes } )
                )
            );
        }
    } );

} )( window.wp );
