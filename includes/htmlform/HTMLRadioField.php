<?php
/**
 * Radio checkbox fields.
 */
class HTMLRadioField extends HTMLFormField {

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( ! is_string( $value ) && ! is_int( $value ) ) {
			return false;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->mParams[ 'options' ] );

		if ( in_array( $value, $validOptions ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	/**
	 * This returns a block of all the radio options, in one cell.
	 * @see includes/HTMLFormField#getInputHTML()
	 *
	 * @param $value String
	 *
	 * @return String
	 */
	function getInputHTML( $value ) {
		$html = $this->formatOptions( $this->mParams[ 'options' ], $value );

		return $html;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = array();
		if ( ! empty( $this->mParams[ 'disabled' ] ) ) {
			$attribs[ 'disabled' ] = 'disabled';
		}

		# TODO: should this produce an unordered list perhaps?
		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', array(), $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeId( $this->mID . "-$info" );
				$radio = Xml::radio( $this->mName, $info, $info == $value, $attribs + array( 'id' => $id ) );
				$radio .= '&#160;' . Html::rawElement( 'label', array( 'for' => $id ), $label );

				$html .= ' ' . Html::rawElement( 'div', array( 'class' => 'mw-htmlform-flatlist-item' ), $radio );
			}
		}

		return $html;
	}

	protected function needsLabel() {
		return false;
	}
}