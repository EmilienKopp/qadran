

export class GridButton {
    init(props) {
      this.eGui = document.createElement('button');
      this.eGui.type = 'button';
      this.eGui.title = props.colDef.tooltipField;
      this.eGui.innerHTML = props.label || 'Click Me';
      this.eGui.className = `btn btn-sm btn-${props.colDef.variant || 'primary'} max-h-6 -mt-2`;
      this.eGui.addEventListener('click', () => {
        if (props.onClick) {
          console.log("props", props);
          props.onClick(props.data);
        }
      });
    }
    
    getGui() {
      return this.eGui;
    }

    refresh(params) {
      return false;
    }
}