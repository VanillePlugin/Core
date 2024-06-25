# ❕ Code of Conduct

The "librairies" folder (lib) contains VanillePlugin helper classes.  

These classes **SHOULD NOT** include:
- Core (VanillePluginValidator)

The "traits" folder (tr) contains VanillePlugin "librairies" traits.

These classes **SHOULD NOT** include:
- Exceptions (exc)
- Core (VanillePluginConfig, VanillePluginOption, VanillePluginValidator)

The "interfaces" folder (int) contains VanillePlugin "librairies" interfaces.

These classes **CAN ONLY** be used by:
- librairies (lib)

The "includes" folder (inc) contains VanillePlugin wrapper classes,  
Used to improve or change behavior of built-in PHP & WordPress functions.

These classes **SHOULD NOT** include any of VanillePlugin parts

The "exceptions" folder (exc) contains VanillePlugin core exceptions.

These classes **SHOULD NOT** include any of VanillePlugin parts
and **CAN ONLY** be used by:
- librairies (lib)
- Traits (tr)
- Core (VanillePluginConfig, VanillePluginOption, VanillePluginValidator)

The "core" folder contains VanillePlugin main classes and traites.

The VanillePluginConfig **SHOULD NOT** include:
- Exceptions (exc)
- Includes (inc)
- Interfaces (int)
- librairies (lib)
- Core (VanillePluginOption)

The VanillePluginOption **SHOULD NOT** include:
- Exceptions (exc)
- Includes (inc)
- Interfaces (int)
- librairies (lib)
- Core (VanillePluginValidator)

The VanillePluginValidator **SHOULD NOT** include:
- Interfaces (int)
- librairies (lib)
- Core (VanillePluginConfig, VanillePluginOption)