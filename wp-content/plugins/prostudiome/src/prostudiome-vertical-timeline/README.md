# Vertical Timeline Block

A beautiful vertical timeline block for WordPress Gutenberg editor that allows you to create historical timelines with editable items.

## Features

- **Fully Editable**: Edit timeline items directly in the WordPress editor
- **Inspector Controls**: Manage all timeline items from the right sidebar panel
- **Responsive Design**: Vertical on mobile, alternating layout on desktop
- **Animated Entrance**: Timeline items fade in with staggered animations
- **Reorderable Items**: Move items up and down to change their order
- **Pre-populated Data**: Comes with complete vaccine history timeline as default content

## How to Use

### Adding the Block

1. Open the WordPress editor
2. Click the **+** button to add a new block
3. Search for **"Vertical Timeline"**
4. Click to insert the block

### Managing Timeline Items

#### In the Inspector Panel (Right Sidebar):

1. **View Items**: Click on any timeline item title to expand/collapse it
2. **Edit Items**: 
   - Edit the **Title** field (e.g., "1796 – prvo cepivo v zgodovini")
   - Edit the **Text** field (description/details)
3. **Reorder Items**: Use the up/down arrow buttons to reorder items
4. **Delete Items**: Click the trash icon to remove an item
5. **Add New Items**: Click the **"Add Timeline Item"** button at the bottom

### Block Settings

- **Alignment**: Choose between Wide or Full width alignment from the toolbar
- The block comes pre-populated with 14 historical vaccine timeline items

## Default Content

The block includes a complete timeline of vaccine history from 1796 to 2020, including:
- 1796 – prvo cepivo v zgodovini
- 1896 – prvo inaktivirano cepivo
- 1932 – prvo cepivo z adjuvansi
- ... and 11 more historical milestones

## Styling

### Desktop View (768px+):
- Timeline items alternate left and right
- Central vertical line with dots marking each event
- Maximum width of 1200px (or wider with alignment settings)

### Mobile View:
- All items aligned to the left
- Simplified vertical layout
- Touch-friendly interface

## Technical Details

- **Block Name**: `prostudiome/vertical-timeline`
- **Category**: Widgets
- **Dynamic Block**: Uses PHP rendering for optimal performance
- **Supports**: Wide and Full alignments
- **Animation**: Fade-in with staggered delays (0.1s increments)

## Customization

You can customize the appearance by editing:
- `style.scss` - Frontend styles
- `editor.scss` - Editor-specific styles
- Colors, spacing, and animations can be adjusted in the SCSS files

After making changes, rebuild with:
```bash
npm run build
```

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design works on all screen sizes
- Animations use CSS3 transforms and opacity

