import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	CheckboxControl,
	RangeControl,
	Disabled,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

import './editor.scss';

// Mismos valores y orden que Render_Personal::get_campos_labels() (PHP).
const CAMPOS = [
	['foto', __('Foto', 'personal-block')],
	['rol', __('Rol en la unidad', 'personal-block')],
	['grado', __('Grado alcanzado', 'personal-block')],
	['unidad', __('Unidad de investigación', 'personal-block')],
	['email', __('Email', 'personal-block')],
	['telefono', __('Teléfono', 'personal-block')],
	['afiliaciones', __('Afiliaciones', 'personal-block')],
	['redes', __('Redes sociales y CV', 'personal-block')],
];

export default function Edit({ attributes, setAttributes }) {
	const { orderBy, categories, columns, layout, campos } = attributes;

	const orderOptions = [
		{ label: __('Orden Manual', 'personal-block'), value: 'menu_order-asc' },
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

	// Guarda los campos respetando el orden de CAMPOS, así el atributo no depende
	// del orden en que se fueron tildando.
	const toggleCampo = (campo, isChecked) => {
		setAttributes({
			campos: CAMPOS.map(([valor]) => valor).filter((valor) =>
				valor === campo ? isChecked : campos.includes(valor)
			),
		});
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Visualización', 'personal-block')}>
					<SelectControl
						label={__('Vista', 'personal-block')}
						value={layout}
						options={[
							{ label: __('Cartas', 'personal-block'), value: 'carta' },
							{ label: __('Lista', 'personal-block'), value: 'lista' },
							{ label: __('Tabla', 'personal-block'), value: 'tabla' },
						]}
						onChange={(newLayout) => setAttributes({ layout: newLayout })}
					/>
					{layout === 'carta' && (
						<RangeControl
							label={__('Columnas', 'personal-block')}
							value={columns}
							onChange={(newColumns) =>
								setAttributes({ columns: newColumns })
							}
							min={1}
							max={4}
						/>
					)}
				</PanelBody>
				<PanelBody title={__('Datos a mostrar', 'personal-block')}>
					<p>
						{__(
							'El nombre se muestra siempre. Si no marcás ningún dato, se muestra solo el nombre.',
							'personal-block'
						)}
					</p>
					{CAMPOS.map(([valor, label]) => (
						<CheckboxControl
							key={valor}
							label={label}
							checked={campos.includes(valor)}
							onChange={(isChecked) => toggleCampo(valor, isChecked)}
						/>
					))}
				</PanelBody>
				<PanelBody
					title={__('Opciones de ordenamiento', 'personal-block')}
					initialOpen={false}
				>
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
					{allCategories && allCategories.length > 0 ? (
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
						))
					) : (
						<p>{__('No hay categorías creadas', 'personal-block')}</p>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				{/* Disabled evita que los links de la vista previa naveguen al hacer clic */}
				<Disabled>
					<ServerSideRender
						block="create-block/wp-personal-block"
						attributes={attributes}
					/>
				</Disabled>
			</div>
		</>
	);
}
