import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { orderBy } = attributes;

	const orderOptions = [
		{ label: __('Name (A-Z)', 'personal-block'), value: 'title-asc' },
		{ label: __('Name (Z-A)', 'personal-block'), value: 'title-desc' },
		{ label: __('Date (Newest first)', 'personal-block'), value: 'date-desc' },
		{ label: __('Date (Oldest first)', 'personal-block'), value: 'date-asc' },
		{ label: __('Modified (Newest first)', 'personal-block'), value: 'modified-desc' },
		{ label: __('Modified (Oldest first)', 'personal-block'), value: 'modified-asc' },
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Sort Options', 'personal-block')}>
					<SelectControl
						label={__('Order by', 'personal-block')}
						value={orderBy}
						options={orderOptions}
						onChange={(newOrderBy) => setAttributes({ orderBy: newOrderBy })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				<ServerSideRender
					block="create-block/personal-block"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
