# ❕ Code of Conduct

The "librairies" folder (lib) contains VanillePlugin helper classes.  

These classes **SHOULD NOT** include:
- Core (VanillePluginValidator)

The "traits" folder (tr) contains VanillePlugin "librairies" traits.

These classes **SHOULD NOT** include:
- Exceptions (exc)
- Core (VanillePluginValidator)

The "third-parties" folder (third) contains VanillePlugin third-party wrapper classes.

These classes **SHOULD NOT** include:
- Exceptions (exc)
- Interfaces (int)
- librairies (lib)
- Core (VanillePluginConfig, VanillePluginOption, VanillePluginValidator)

The "interfaces" folder (int) contains VanillePlugin "librairies" interfaces.

These classes **CAN ONLY** be used by:
- librairies (lib)

The "includes" folder (inc) contains VanillePlugin wrapper classes,  
Used to improve or change behavior of built-in PHP & WordPress functions.

These classes **SHOULD NOT** include:
- Exceptions (exc)
- Interfaces (int)
- librairies (lib)
- Third-parties (third)
- Traits (tr)
- Core (VanillePluginConfig, VanillePluginOption, VanillePluginValidator)

The "exceptions" folder (exc) contains VanillePlugin core exceptions.

These classes **SHOULD NOT** include:
- Interfaces (int)
- librairies (lib)
- Third-parties (third)
- Traits (tr)
- Core (VanillePluginConfig, VanillePluginOption, VanillePluginValidator)

The "core" folder contains VanillePlugin main classes and traites.

The VanillePluginConfig **SHOULD NOT** include:
- Exceptions (exc)
- Includes (inc)
- Interfaces (int)
- librairies (lib)
- Third-parties (third)
- Core (VanillePluginOption)

The VanillePluginOption **SHOULD NOT** include:
- Exceptions (exc)
- Includes (inc)
- Interfaces (int)
- librairies (lib)
- Third-parties (third)
- Core (VanillePluginValidator)

The VanillePluginValidator **SHOULD NOT** include:
- Interfaces (int)
- librairies (lib)
- Third-parties (third)
- Core (VanillePluginConfig, VanillePluginOption)