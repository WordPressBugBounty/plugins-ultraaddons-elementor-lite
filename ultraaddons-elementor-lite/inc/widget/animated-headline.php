<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Animated_Headline extends Base{
    
    
    /**
     * Get your widget name
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons','ua', 'headline', 'heading', 'animation', 'title', 'text' ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        
        $this->ua_content_general_controls();
        $this->ua_style_controls();
       
    }
    
    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.9
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $tag = ultraaddons_validate_html_tag( $settings['tag'] );//Utils::validate_html_tag( $settings['tag'] );
        
        $this->add_render_attribute( 'headline', 'class', 'elementor-headline' );
        if ( 'rotate' === $settings['headline_style'] ) {
            $this->add_render_attribute( 'headline', 'class', 'elementor-headline-animation-type-' . $settings['animation_type'] );
            $is_letter_animation = in_array( $settings['animation_type'], [ 'typing', 'swirl', 'blinds', 'wave' ] );
            if ( $is_letter_animation ) {
				$this->add_render_attribute( 'headline', 'class', 'elementor-headline-letters' );
			}
        }

        if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			echo '<a ' . $this->get_render_attribute_string( 'url' ) . '>';
		}
        ?>

        <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'headline' ); ?>>
        <?php if ( ! empty( $settings['before_text'] ) ) : ?>
			<span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php echo $settings['before_text']; ?></span>
		<?php endif; ?>
		<span class="elementor-headline-dynamic-wrapper elementor-headline-text-wrapper">
			<?php if ( 'rotate' === $settings['headline_style'] && $settings['rotating_text'] ) :
				$rotating_text = explode( "\n", $settings['rotating_text'] );
				foreach ( $rotating_text as $key => $text ) :
					$status_class = 1 > $key ? 'elementor-headline-text-active' : ''; ?>
				<span class="elementor-headline-dynamic-text <?php echo $status_class; ?>">
					<?php echo str_replace( ' ', '&nbsp;', $text ); ?>
				</span>
			<?php endforeach; ?>
			<?php elseif ( 'highlight' === $settings['headline_style'] && ! empty( $settings['highlighted_text'] ) ) : ?>
				<span class="elementor-headline-dynamic-text elementor-headline-text-active"><?php echo $settings['highlighted_text']; ?></span>
			<?php endif ?>
		</span>
        <?php if ( ! empty( $settings['after_text'] ) ) : ?>
			<span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php echo $settings['after_text']; ?></span>
        <?php endif; ?>
        </span>
        </<?php echo $tag; ?>>
		<?php

		if ( ! empty( $settings['link']['url'] ) ) {
			echo '</a>';
		}

        
    }

	/**
	 * Render Animated Headline widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.9
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		function uaValidateHTMLTag(tag) {
			var validHTMLTags = ['article', 'aside', 'div', 'footer', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'main', 'nav', 'p', 'section', 'span'];
			return validHTMLTags.includes(tag.toLowerCase()) ? tag : 'div';
		}
		var headlineClasses = 'elementor-headline',
			tag = uaValidateHTMLTag( settings.tag );

		if ( 'rotate' === settings.headline_style ) {
			headlineClasses += ' elementor-headline-animation-type-' + settings.animation_type;

			var isLetterAnimation = -1 !== [ 'typing', 'swirl', 'blinds', 'wave' ].indexOf( settings.animation_type );

			if ( isLetterAnimation ) {
				headlineClasses += ' elementor-headline-letters';
			}
		}

		if ( settings.link.url ) { #>
			<a href="#">
		<# } #>
				<{{{ tag }}} class="{{{ headlineClasses }}}">
					<# if ( settings.before_text ) { #>
						<span class="elementor-headline-plain-text elementor-headline-text-wrapper">{{{ settings.before_text }}}</span>
					<# } #>

					<# if ( settings.rotating_text ) { #>
						<span class="elementor-headline-dynamic-wrapper elementor-headline-text-wrapper">
						<# if ( 'rotate' === settings.headline_style && settings.rotating_text ) {
							var rotatingText = ( settings.rotating_text || '' ).split( '\n' );
							for ( var i = 0; i < rotatingText.length; i++ ) {
								var statusClass = 0 === i ? 'elementor-headline-text-active' : ''; #>
								<span class="elementor-headline-dynamic-text {{ statusClass }}">
									{{{ rotatingText[ i ].replace( ' ', '&nbsp;' ) }}}
								</span>
							<# }
						}

						else if ( 'highlight' === settings.headline_style && settings.highlighted_text ) { #>
							<span class="elementor-headline-dynamic-text elementor-headline-text-active">{{{ settings.highlighted_text }}}</span>
						<# } #>
						</span>
					<# } #>

					<# if ( settings.after_text ) { #>
						<span class="elementor-headline-plain-text elementor-headline-text-wrapper">{{{ settings.after_text }}}</span>
					<# } #>
				</{{{ tag }}}>
		<# if ( settings.link.url ) { #>
			</a>
		<# } #>
		<?php
	}

    protected function ua_content_general_controls(){
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'headline_style',
			[
				'label' => __( 'Style', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'highlight',
				'options' => [
					'highlight' => __( 'Highlighted', 'ultraaddons' ),
					'rotate' => __( 'Rotating', 'ultraaddons' ),
				],
				'prefix_class' => 'elementor-headline--style-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

        $this->add_control(
			'animation_type',
			[
				'label' => __( 'Animation', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'typing' => 'Typing',
					'clip' => 'Clip',
					'flip' => 'Flip',
					'swirl' => 'Swirl',
					'blinds' => 'Blinds',
					'drop-in' => 'Drop-in',
					'wave' => 'Wave',
					'slide' => 'Slide',
					'slide-down' => 'Slide Down',
				],
				'default' => 'typing',
				'condition' => [
					'headline_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

        $this->add_control(
			'marker',
			[
				'label' => __( 'Shape', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => _x( 'Circle', 'Shapes', 'ultraaddons' ),
					'curly' => _x( 'Curly', 'Shapes', 'ultraaddons' ),
					'underline' => _x( 'Underline', 'Shapes', 'ultraaddons' ),
					'double' => _x( 'Double', 'Shapes', 'ultraaddons' ),
					'double_underline' => _x( 'Double Underline', 'Shapes', 'ultraaddons' ),
					'underline_zigzag' => _x( 'Underline Zigzag', 'Shapes', 'ultraaddons' ),
					'diagonal' => _x( 'Diagonal', 'Shapes', 'ultraaddons' ),
					'strikethrough' => _x( 'Strikethrough', 'Shapes', 'ultraaddons' ),
					'x' => 'X',
				],
				'render_type' => 'template',
				'condition' => [
					'headline_style' => 'highlight',
				],
				'frontend_available' => true,
			]
		);

        $this->add_control(
			'before_text',
			[
				'label' => __( 'Before Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'default' => __( 'This page is', 'ultraaddons' ),
				'placeholder' => __( 'Enter your headline', 'ultraaddons' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'highlighted_text',
			[
				'label' => __( 'Highlighted Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'default' => __( 'Amazing', 'ultraaddons' ),
				'label_block' => true,
				'condition' => [
					'headline_style' => 'highlight',
				],
				'separator' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'rotating_text',
			[
				'label' => __( 'Rotating Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter each word in a separate line', 'ultraaddons' ),
				'separator' => 'none',
				'default' => "Better\nBigger\nFaster",
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'condition' => [
					'headline_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'after_text',
			[
				'label' => __( 'After Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'placeholder' => __( 'Enter your headline', 'ultraaddons' ),
				'label_block' => true,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Infinite Loop', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--iteration-count: infinite',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlight_animation_duration',
			[
				'label' => __( 'Duration', 'ultraaddons' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 1200,
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--animation-duration: {{VALUE}}ms',
				],
				'condition' => [
					'headline_style' => 'highlight',
				],
			]
		);

		$this->add_control(
			'highlight_iteration_delay',
			[
				'label' => __( 'Delay', 'ultraaddons' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 8000,
				'render_type' => 'template',
				'frontend_available' => true,
				'condition' => [
					'headline_style' => 'highlight',
					'loop' => 'yes',
				],
			]
		);

		$this->add_control(
			'rotate_iteration_delay',
			[
				'label' => __( 'Duration', 'ultraaddons' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 2500,
				'render_type' => 'template',
				'frontend_available' => true,
				'condition' => [
					'headline_style' => 'rotate',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ultraaddons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-headline' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tag',
			[
				'label' => __( 'HTML Tag', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

        $this->end_controls_section();
    }

    protected function ua_style_controls(){
        $this->start_controls_section(
			'section_style_marker',
			[
				'label' => __( 'Shape', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'headline_style' => 'highlight',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'stroke_width',
			[
				'label' => __( 'Width', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'above_content',
			[
				'label' => __( 'Bring to Front', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper svg' => 'z-index: 2',
					'{{WRAPPER}} .elementor-headline-dynamic-text' => 'z-index: auto',
				],
			]
		);

		$this->add_control(
			'rounded_edges',
			[
				'label' => __( 'Rounded Edges', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke-linecap: round; stroke-linejoin: round',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label' => __( 'Headline', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-plain-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-headline',
			]
		);

		$this->add_control(
			'heading_words_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Animated Text', 'ultraaddons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'words_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--dynamic-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'words_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-headline-dynamic-text',
				'exclude' => [ 'font_size' ],
			]
		);

		$this->add_control(
			'typing_animation_highlight_colors',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Selected Text', 'ultraaddons' ),
				'separator' => 'before',
				'condition' => [
					'headline_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'highlighted_text_background_color',
			[
				'label' => __( 'Selection Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-bg-color: {{VALUE}}',
				],
				'condition' => [
					'headline_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'highlighted_text_color',
			[
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-color: {{VALUE}}',
				],
				'condition' => [
					'headline_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->end_controls_section();
    }
            
            
            
}
        