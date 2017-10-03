# bp-progenitor
A starter standalone theme for BuddyPress based on the next generation template packs work at:
https://github.com/buddypress/next-template-packs

## Outline
Progenitor is a complete theme based around the next generation BP template packs known in their first iteration as 'Nouveau' the current default template files for BP core plugin.

The intention with Progenitor is for a theme capable of replacing the older BP-Default theme and is complete as a WP theme while still retaining the BP theme compatibility layer. It affords us the opportunity to build out specific theme styles and layouts free of the constraint of theme compatibilty needing to fit in to an unkown themes styles and layout and the restrictions that necessarily imposes on developing styles & layouts.

It is considered a 'Starter Theme' as it should be able to be taken and further developed with custom styles worked up as a child theme.

The theme uses a modified version of BP Nouveau and Underscores for the primary WP template components, the Underscores implementation is being heavily modified for use with the BP.

## Versions
Version: current version is considered a pre-alpha and undergoing adhoc development as time permits and stands at v0.1.0 pre-alpha.

## Development
Development will be in the `dev` branch, merging to `master` and creating a `tag` version when a suitable milestone point reached, for the moment while initially hacking the basics around commits are being made directly to master. 

## Installing
Currently the theme is in very early development but does mainly function fully as a WP/BP set of style & templates. Either clone the repo or download zip and install/use as you would any WP theme.
One caveat though at present the necessary BP theme registration has to be performed by hacking the `bp-core-theme-compatibility.php` file to add:
```php
add_action( 'bp_register_theme_packages', function() {
  bp_register_theme_package( array(
   'id'      => 'progenitor',
   'name'    => __( 'BuddyPress Progenitor', 'buddypress' ),
   'version' => bp_get_version(),
   'dir'     => trailingslashit( get_template_directory() . '/community' ),
   'url'     => trailingslashit( get_template_directory_uri() . '/community' ),
  ) );
} );
```
	
	This is an aspect to be tackled later rather than hold up development.
	
	## Early view screenies
