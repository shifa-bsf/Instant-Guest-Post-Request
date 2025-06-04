/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const FormField = ({ 
  label, 
  id, 
  type = 'text', 
  value, 
  onChange, 
  tooltip = '', 
  options = [], 
  placeholder = '',
  rows = 4,
  disabled = false,
  className = '',
  required = false
}) => {
  const handleChange = (e) => {
    const newValue = type === 'checkbox' ? e.target.checked : e.target.value;
    onChange(newValue);
  };

  return (
    <div className={`igpr-mb-4 ${className}`}>
      <div className="igpr-flex igpr-items-center">
        {type !== 'checkbox' && (
          <label htmlFor={id} className="igpr-block igpr-text-sm igpr-font-medium igpr-text-gray-700 igpr-mb-1">
            {label}
            {required && <span className="igpr-text-red-500">*</span>}
          </label>
        )}
        
        {tooltip && (
          <div className="igpr-relative igpr-inline-block">
            <span className="igpr-ml-1 igpr-text-gray-400 hover:igpr-text-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" className="igpr-h-4 igpr-w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </span>
            <span className="igpr-invisible igpr-absolute igpr-z-10 igpr-w-64 igpr-bg-gray-800 igpr-text-white igpr-text-xs igpr-rounded igpr-p-2 igpr-opacity-0 igpr-transition-opacity igpr-duration-300 igpr-bottom-full igpr-left-1/2 igpr-transform igpr--translate-x-1/2 igpr--translate-y-2 group-hover:igpr-visible group-hover:igpr-opacity-100">
              {tooltip}
            </span>
          </div>
        )}
      </div>

      {type === 'text' && (
        <input
          type="text"
          id={id}
          value={value || ''}
          onChange={handleChange}
          className="igpr-mt-1 igpr-block igpr-w-full igpr-rounded-md igpr-border-wp-border igpr-shadow-sm focus:igpr-ring-wp-primary focus:igpr-border-wp-primary igpr-text-sm"
          placeholder={placeholder}
          disabled={disabled}
        />
      )}

      {type === 'textarea' && (
        <textarea
          id={id}
          value={value || ''}
          onChange={handleChange}
          rows={rows}
          className="igpr-mt-1 igpr-block igpr-w-full igpr-rounded-md igpr-border-wp-border igpr-shadow-sm focus:igpr-ring-wp-primary focus:igpr-border-wp-primary igpr-text-sm"
          placeholder={placeholder}
          disabled={disabled}
        />
      )}

      {type === 'select' && (
        <select
          id={id}
          value={value || ''}
          onChange={handleChange}
          className="igpr-mt-1 igpr-block igpr-w-full igpr-rounded-md igpr-border-wp-border igpr-shadow-sm focus:igpr-ring-wp-primary focus:igpr-border-wp-primary igpr-text-sm"
          disabled={disabled}
        >
          {options.map((option) => (
            <option key={option.value} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
      )}

      {type === 'checkbox' && (
        <div className="igpr-flex igpr-items-center">
          <input
            type="checkbox"
            id={id}
            checked={value || false}
            onChange={handleChange}
            className="igpr-h-4 igpr-w-4 igpr-text-wp-primary focus:igpr-ring-wp-primary igpr-border-wp-border igpr-rounded"
            disabled={disabled}
          />
          <label htmlFor={id} className="igpr-ml-2 igpr-block igpr-text-sm igpr-text-gray-700">
            {label}
          </label>
        </div>
      )}
    </div>
  );
};

export default FormField;
