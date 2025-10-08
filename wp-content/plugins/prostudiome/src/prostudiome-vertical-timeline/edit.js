import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	Icon,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { trash, plus } from "@wordpress/icons";

export default function Edit({ attributes, setAttributes }) {
	const { timelineItems } = attributes;
	const blockProps = useBlockProps();
	const [expandedItem, setExpandedItem] = useState(null);

	const addTimelineItem = () => {
		const newItem = {
			id: Date.now(),
			title: "",
			text: "",
		};
		setAttributes({
			timelineItems: [...timelineItems, newItem],
		});
		setExpandedItem(newItem.id);
	};

	const updateTimelineItem = (id, field, value) => {
		const updatedItems = timelineItems.map((item) =>
			item.id === id ? { ...item, [field]: value } : item
		);
		setAttributes({ timelineItems: updatedItems });
	};

	const removeTimelineItem = (id) => {
		const updatedItems = timelineItems.filter((item) => item.id !== id);
		setAttributes({ timelineItems: updatedItems });
		if (expandedItem === id) {
			setExpandedItem(null);
		}
	};

	const moveItem = (index, direction) => {
		const newItems = [...timelineItems];
		const targetIndex = direction === "up" ? index - 1 : index + 1;

		if (targetIndex < 0 || targetIndex >= newItems.length) {
			return;
		}

		[newItems[index], newItems[targetIndex]] = [
			newItems[targetIndex],
			newItems[index],
		];
		setAttributes({ timelineItems: newItems });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__("Timeline Items", "prostudiome")}
					initialOpen={true}
				>
					<div style={{ marginBottom: "20px" }}>
						{timelineItems.map((item, index) => (
							<div
								key={item.id}
								style={{
									marginBottom: "15px",
									padding: "15px",
									border: "1px solid #ddd",
									borderRadius: "4px",
									backgroundColor:
										expandedItem === item.id ? "#f0f0f0" : "#fff",
								}}
							>
								<div
									style={{
										display: "flex",
										justifyContent: "space-between",
										alignItems: "center",
										marginBottom: expandedItem === item.id ? "10px" : "0",
									}}
								>
									<strong
										style={{
											cursor: "pointer",
											flex: 1,
											fontSize: "14px",
										}}
										onClick={() =>
											setExpandedItem(
												expandedItem === item.id ? null : item.id
											)
										}
									>
										{item.title || __("(No title)", "prostudiome")}
									</strong>
									<div style={{ display: "flex", gap: "5px" }}>
										{index > 0 && (
											<Button
												isSmall
												onClick={() => moveItem(index, "up")}
												icon="arrow-up-alt2"
											/>
										)}
										{index < timelineItems.length - 1 && (
											<Button
												isSmall
												onClick={() => moveItem(index, "down")}
												icon="arrow-down-alt2"
											/>
										)}
										<Button
											isDestructive
											isSmall
											onClick={() => removeTimelineItem(item.id)}
											icon={trash}
										/>
									</div>
								</div>

								{expandedItem === item.id && (
									<div>
										<TextControl
											label={__("Title", "prostudiome")}
											value={item.title}
											onChange={(value) =>
												updateTimelineItem(item.id, "title", value)
											}
											placeholder={__(
												"e.g., 1796 – prvo cepivo v zgodovini",
												"prostudiome"
											)}
										/>
										<TextareaControl
											label={__("Text", "prostudiome")}
											value={item.text}
											onChange={(value) =>
												updateTimelineItem(item.id, "text", value)
											}
											rows={5}
											placeholder={__(
												"Enter the description...",
												"prostudiome"
											)}
										/>
									</div>
								)}
							</div>
						))}
					</div>

					<Button
						variant="primary"
						onClick={addTimelineItem}
						icon={plus}
						style={{ width: "100%" }}
					>
						{__("Add Timeline Item", "prostudiome")}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="vertical-timeline-preview">
					<h3 style={{ marginBottom: "20px", color: "#666" }}>
						{__("Vertical Timeline", "prostudiome")}
					</h3>
					{timelineItems.length === 0 ? (
						<p style={{ textAlign: "center", color: "#999" }}>
							{__(
								"No timeline items yet. Add some using the panel on the right.",
								"prostudiome"
							)}
						</p>
					) : (
						<div className="timeline-items-preview">
							{timelineItems.map((item) => (
								<div
									key={item.id}
									className="timeline-item-preview"
									style={{
										marginBottom: "20px",
										paddingLeft: "30px",
										borderLeft: "3px solid #0073aa",
										position: "relative",
									}}
								>
									<div
										style={{
											position: "absolute",
											left: "-8px",
											top: "5px",
											width: "12px",
											height: "12px",
											borderRadius: "50%",
											backgroundColor: "#0073aa",
											border: "2px solid white",
											boxShadow: "0 0 0 2px #0073aa",
										}}
									/>
									<h4 style={{ marginBottom: "10px", color: "#0073aa" }}>
										{item.title || __("(No title)", "prostudiome")}
									</h4>
									<p style={{ margin: 0, color: "#666" }}>
										{item.text || __("(No text)", "prostudiome")}
									</p>
								</div>
							))}
						</div>
					)}
				</div>
			</div>
		</>
	);
}

