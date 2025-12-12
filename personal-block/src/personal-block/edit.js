import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	CheckboxControl,
	RangeControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { orderBy, categories, columns } = attributes;

	const orderOptions = [
		{ label: __('Name (A-Z)', 'personal-block'), value: 'title-asc' },
		{ label: __('Name (Z-A)', 'personal-block'), value: 'title-desc' },
		{ label: __('Date (Newest first)', 'personal-block'), value: 'date-desc' },
		{ label: __('Date (Oldest first)', 'personal-block'), value: 'date-asc' },
		{
			label: __('Modified (Newest first)', 'personal-block'),
			value: 'modified-desc',
		},
		{
			label: __('Modified (Oldest first)', 'personal-block'),
			value: 'modified-asc',
		},
	];

	const allCategories = useSelect((select) => {
		return select(coreStore).getEntityRecords('taxonomy', 'categorias', {
			per_page: -1,
		});
	}, []);

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
				<PanelBody
					title={__('Category Settings', 'personal-block')}
					initialOpen={false}
				>
					{allCategories &&
						allCategories.map((category) => (
							<CheckboxControl
								key={category.id}
								label={category.name}
								checked={categories.includes(category.id)}
								onChange={(isChecked) => {
									const newCategories = isChecked
										? [...categories, category.id]
										: categories.filter(
												(id) => id !== category.id
										  );
									setAttributes({ categories: newCategories });
								}}
							/>
						))}
				</PanelBody>
				<PanelBody
					title={__('Layout Settings', 'personal-block')}
					initialOpen={false}
				>
					<RangeControl
						label={__('Columns', 'personal-block')}
						value={columns}
						onChange={(newColumns) =>
							setAttributes({ columns: newColumns })
						}
						min={1}
						max={4}
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