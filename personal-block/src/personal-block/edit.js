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
		{ label: __('Nombre (A-Z)', 'personal-block'), value: 'title-asc' },
		{ label: __('Nombre (Z-A)', 'personal-block'), value: 'title-desc' },
		{ label: __('Fecha (más nuevos primero)', 'personal-block'), value: 'date-desc' },
		{ label: __('Fecha (más antiguos primero)', 'personal-block'), value: 'date-asc' },
		{
			label: __('Modificado (más nuevos primero)', 'personal-block'),
			value: 'modified-desc',
		},
		{
			label: __('Modificado (más antiguos primero)', 'personal-block'),
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
				<PanelBody title={__('Opciones de ordenamiento', 'personal-block')}>
					<SelectControl
						label={__('Ordenar por', 'personal-block')}
						value={orderBy}
						options={orderOptions}
						onChange={(newOrderBy) => setAttributes({ orderBy: newOrderBy })}
					/>
				</PanelBody>
				<PanelBody
					title={__('Seleccionar categoría', 'personal-block')}
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
					title={__('Cantidad de columnas', 'personal-block')}
					initialOpen={false}
				>
					<RangeControl
						label={__('Columnas', 'personal-block')}
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
